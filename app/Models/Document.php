<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    public function lcRequest()
    {
        return $this->belongsTo(LCRequest::class, 'lc_request_id');
    }

}
