<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Company;
use App\Models\LCRequest;
use Illuminate\Http\Request;
use App\Models\AmendmentLCRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
   
    public function index() {
      
        $toDate = Carbon::now();
        $fromDate = $toDate->copy()->subYear()->startOfDay(); // Use copy() to keep the original $toDate unchanged
        $toDateEndOfDay = $toDate->endOfDay();
        $colors = array('bg-success','bg-danger','bg-warning','bg-info'); 
    
        // Prepare the SQL query for pending LC requests
        $pendingLcRequestsSql = "
                SELECT companies.id AS company_id,companies.name AS company_name,COUNT(lc_request.id) AS pending_lc_request
                FROM companies
                LEFT JOIN lc_request ON companies.id = lc_request.company_id 
                AND lc_request.created_at >= :fromDate
                AND lc_request.created_at <= :toDateEndOfDay
                AND lc_request.status_id != 10
                GROUP BY companies.id
                ORDER BY companies.id
                ";
       

        $pendingLcRequests = DB::select($pendingLcRequestsSql,[
            'fromDate' => $fromDate,
            'toDateEndOfDay' => $toDateEndOfDay,
        ]);

        // Calculate the total pending LC requests
        $totalPendingLcRequests = array_sum(array_column($pendingLcRequests, 'pending_lc_request'));
        
        $monthWiseAmountsSql = "
        SELECT
            MONTH(lc_request.created_at) AS month_number,
            MONTHNAME(lc_request.created_at) AS month_name, 
            COALESCE(SUM(amount), 0) AS total_amount
        FROM
            lc_request
        WHERE
            created_at >= :fromDate
            AND created_at <= :toDateEndOfDay
            AND status_id = 10
          GROUP BY month_number, month_name
          HAVING total_amount > 0
          ORDER BY month_number DESC
        ";
    
        // Execute the query using Laravel's DB facade
        $tranmittedLCmonthWiseAmounts = DB::select($monthWiseAmountsSql, [
            'fromDate' => $fromDate,
            'toDateEndOfDay' => $toDateEndOfDay,
        ]);
    
       $pendingAmendment = "
       SELECT companies.id as company_id, companies.name as company_name, COUNT(amendment_lc_request.id) as amendment_count
       FROM companies
       LEFT JOIN lc_request ON companies.id = lc_request.company_id 
       LEFT JOIN amendment_lc_request ON amendment_lc_request.lc_request_id = lc_request.id 
       AND amendment_lc_request.created_at >= :fromDate
       AND amendment_lc_request.created_at <= :toDateEndOfDay
       AND amendment_lc_request.status_id != 10
       GROUP BY companies.id
       ORDER BY companies.id
       ";

       $pendingAmendment = DB::select($pendingAmendment, [
        'fromDate' => $fromDate,
        'toDateEndOfDay' => $toDateEndOfDay,
        ]);

        $totalAmendmentRequests = array_sum(array_column($pendingAmendment, 'amendment_count'));


        $transmittedAmendmentSql = "
        SELECT 
            MONTH(amendment_lc_request.created_at) AS month_number,
            MONTHNAME(amendment_lc_request.created_at) AS month_name, 
            COUNT(DISTINCT CASE WHEN amendment_lc_request.status_id != 10 THEN amendment_lc_request.id END) AS amendment_count
        FROM companies
        LEFT JOIN lc_request ON lc_request.company_id = companies.id
        LEFT JOIN amendment_lc_request ON amendment_lc_request.lc_request_id = lc_request.id
        WHERE amendment_lc_request.created_at >= :fromDate
        AND amendment_lc_request.created_at <= :toDateEndOfDay
        GROUP BY month_number, month_name
        HAVING amendment_count > 0
        ORDER BY month_number DESC
    ";




        $transmittedAmendment = DB::select($transmittedAmendmentSql, [
            'fromDate' => $fromDate,
            'toDateEndOfDay' => $toDateEndOfDay,
            ]);
            

  
    return view('dashboard',compact('pendingLcRequests','tranmittedLCmonthWiseAmounts','totalPendingLcRequests','pendingAmendment','transmittedAmendment','totalAmendmentRequests','colors','transmittedAmendment'));
    }
}

