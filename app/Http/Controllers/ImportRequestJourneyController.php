<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImportRequestJourney;

class ImportRequestJourneyController extends Controller
{
    
    public static function add($import_request_id,$user_id,$status_id,$created_at,$reason_code = null,$comments = null){

        $journey = new ImportRequestJourney();
        $journey->import_request_id = $import_request_id;
        $journey->user_id = $user_id;
        $journey->status_id = $status_id;
        $journey->created_at = $created_at;
        $journey->reason_code = $reason_code;
        $journey->comments = $comments;
        $journey->save();
        
    }
}
