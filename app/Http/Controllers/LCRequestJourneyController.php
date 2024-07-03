<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LCRequestJourney;
use App\Http\Controllers\Controller;

class LCRequestJourneyController extends Controller
{
    public static function add($lc_request_id,$user_id,$status_id,$created_at,$reason_code = null,$amendment_request_id = null){

        $journey = new LCRequestJourney();
        $journey->lc_request_id = $lc_request_id;
        $journey->user_id = $user_id;
        $journey->status_id = $status_id;
        $journey->created_at = $created_at;
        $journey->reason_code = $reason_code;
        $journey->amendment_request_id = $amendment_request_id;
        $journey->save();
        
    }

}
