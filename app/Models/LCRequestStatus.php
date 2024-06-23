<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LCRequestStatus extends Model
{
    use HasFactory;

    protected $table = "lc_request_status";

    protected $fillable = [
        'name'
    ];
}
