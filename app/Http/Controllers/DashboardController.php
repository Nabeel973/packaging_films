<?php

namespace App\Http\Controllers;

use App\Models\LCRequest;
use Illuminate\Http\Request;
use App\Models\AmendmentLCRequest;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(){
        $lc_requests = LCRequest::where('status_id','!=',10)->get();
        $lc_count = count($lc_requests);
        $amendment_lc_count = AmendmentLCRequest::where('status_id','!=',10)->get()->count();
        return view('dashboard',compact('lc_count','amendment_lc_count'));
    }
}
