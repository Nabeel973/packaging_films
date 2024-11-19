<?php // Code within app\Helpers\Helper.php

namespace App;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Helper
{

    public static function uploadDocuments($file, $document, $field_name, $directory_name,$id)
    { 
        if ($file) {
            $uploadedDocument = $file;
            $documentName = time() . '.' . $field_name . '.' . $uploadedDocument->getClientOriginalExtension();
            $documentDirectory = $directory_name . '/' . $id;  // Assuming $document has an id field
            $documentPath = $uploadedDocument->storeAs($documentDirectory, $documentName);

            // Delete the old file if it exists
            if ($document->$field_name) {
                Storage::delete($document->$field_name);
            }

            // Update the document field with the new path
            $document->$field_name = $documentPath;
            $document->save();
        }
    }

    public static function rejectReason($model, $reason = null, $journeyController = null, $journeyMethod = 'add', $emailJob = null)
    {
      
        $status_id = $model->status_id;
      
        if (in_array($status_id,[1,4]) && in_array(Auth::user()->role_id, [1, 3])) {
            $status_id = 3;
        } elseif (in_array($status_id, [2, 4]) && in_array(Auth::user()->role_id, [1, 4])) {
            $status_id = 5;
        }
    
        $model->status_id = $status_id;
        $model->reason_code = $reason;
        $model->updated_at = Carbon::now();
        $model->save();
    
        // Use the specified controller and method for the journey, if provided
        if ($journeyController && method_exists($journeyController, $journeyMethod)) {
            app($journeyController)->$journeyMethod($model->id, Auth::id(), $status_id, Carbon::now(), $reason);
        }
    
        // Dispatch email if provided
        if ($emailJob) {
            $emailJob::dispatch($model);
        }
    
    }
    
}