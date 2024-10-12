<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ClearanceRequestJourney;

class ClearanceRequestJourneyController extends Controller
{
    
    static public function add($clearance_id,$user_id,$status_id,$created_at){
        ClearanceRequestJourney::create(['clearance_id' => $clearance_id,'user_id' => $user_id,'status_id' => $status_id,'created_at' => $created_at]);
    }
}
