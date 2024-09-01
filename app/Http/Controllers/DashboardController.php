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
    
        // Prepare the SQL query for pending LC requests
        $pendingLcRequestsSql = "
            SELECT companies.id company_id,companies.name AS company_name,COUNT(lc_request.id) AS pending_lc_request
            FROM lc_request
            JOIN companies ON companies.id = lc_request.company_id 
            WHERE
            lc_request.created_at >= :fromDate
            AND lc_request.created_at <= :toDateEndOfDay
            AND lc_request.status_id != 10
            GROUP BY companies.id
        ";

        $pendingLcRequests = DB::select($pendingLcRequestsSql,[
            'fromDate' => $fromDate,
            'toDateEndOfDay' => $toDateEndOfDay,
        ]);

        // Calculate the total pending LC requests
        $totalPendingLcRequests = array_sum(array_column($pendingLcRequests, 'pending_lc_request'));
        
        $monthWiseAmountsSql = "
        SELECT
            MONTHNAME(created_at) AS month_name,
            COALESCE(SUM(amount), 0) AS total_amount
        FROM
            lc_request
        WHERE
            created_at >= :fromDate
            AND created_at <= :toDateEndOfDay
            AND status_id = 10
        GROUP BY
           month_name
        ORDER BY
         month_name
    ";
    
    // Execute the query using Laravel's DB facade
    $tranmittedLCmonthWiseAmounts = DB::select($monthWiseAmountsSql, [
        'fromDate' => $fromDate,
        'toDateEndOfDay' => $toDateEndOfDay,
    ]);
    


    // Query for pending amendment counts
    $pendingAmendment = Company::join('lc_request', 'lc_request.company_id', '=', 'companies.id')
    ->leftJoin('amendment_lc_request', 'amendment_lc_request.lc_request_id', '=', 'lc_request.id')
    ->whereBetween('amendment_lc_request.created_at', [$fromDate, $toDateEndOfDay])
    ->where('amendment_lc_request.status_id', '!=', 10)
    ->selectRaw('companies.id, companies.name, COUNT(amendment_lc_request.id) as amendment_count')
    ->groupBy('companies.id', 'companies.name')
    ->get()->toArray();

// Query for transmitted amendment counts
    $transmittedAmendment = Company::join('lc_request', 'lc_request.company_id', '=', 'companies.id')
        ->leftJoin('amendment_lc_request', 'amendment_lc_request.lc_request_id', '=', 'lc_request.id')
        ->whereBetween('amendment_lc_request.created_at', [$fromDate, $toDateEndOfDay])
        ->where('amendment_lc_request.status_id', 10)
        ->selectRaw('MONTHNAME(amendment_lc_request.created_at) AS month_name, COALESCE(SUM(amount), 0) AS total_amount')
        ->groupBy('month_name')
        ->orderBy('month_name')
        ->get()->toArray();

        $totalAmendmentRequests = array_sum(array_column($pendingAmendment, 'amendment_count'));

    return view('dashboard',compact('pendingLcRequests','tranmittedLCmonthWiseAmounts','totalPendingLcRequests','pendingAmendment','transmittedAmendment','totalAmendmentRequests'));
    }
}

