<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LCRequestJourney extends Model
{
    use HasFactory;

    protected $table = "lc_request_journey";

    public $timestamps = ['created_at'];
}
