<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportRequest extends Model
{
    use HasFactory;

    public function journey()
    {
        return $this->hasMany(ImportRequestJourney::class,'import_request_id','id');
    }

    // public function created_by(){
    //     return $this->belongsTo(User::class,'created_by','id');
    // }

    public function status(){
        return $this->belongsTo(ImportRequestStatus::class,'status_id','id');
    }

    public function documents()
    {
        return $this->hasOne(ImportRequestAttachments::class, 'import_request_id', 'id');
    }
}
