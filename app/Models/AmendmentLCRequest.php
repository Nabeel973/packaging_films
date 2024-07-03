<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmendmentLCRequest extends Model
{
    use HasFactory;

    protected $table = 'amendment_lc_request';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function status(){
        return $this->belongsTo(LCRequestStatus::class,'status_id','id');
    }

    public function lcRequest()
    {
        return $this->belongsTo(LCRequest::class,'lc_request_id','id');
    }

}
