<?php // Code within app\Helpers\Helper.php

namespace App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Helper
{

    public static function uploadDocuments($file, $document, $field_name, $directory_name,$lc_request_id)
    {  
        if ($file) {
            $uploadedDocument = $file;
            $documentName = time() . '.' . $field_name . '.' . $uploadedDocument->getClientOriginalExtension();
            $documentDirectory = $directory_name . '/' . $lc_request_id;  // Assuming $document has an id field
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

    public function addJourney(){
        
    }
}