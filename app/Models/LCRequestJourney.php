<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LCRequestJourney extends Model
{
    use HasFactory;

    protected $table = 'lc_request_journey';

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function status(){
        return $this->belongsTo(LCRequestStatus::class,'status_id','id');
    }

    public function lcRequest()
    {
        return $this->belongsTo(LcRequest::class, 'lc_request_id', 'id');
    }


}
