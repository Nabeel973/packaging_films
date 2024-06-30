<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LCRequest extends Model
{
    use HasFactory;

    protected $table = 'lc_request';

     /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function journey()
    {
        return $this->hasMany(LCRequestJourney::class,'lc_request_id','id');
    }

    public function created_by(){
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function status(){
        return $this->belongsTo(LCRequestStatus::class,'status_id','id');
    }

    public function documents()
    {
        return $this->hasOne(Document::class, 'lc_request_id', 'id');
    }
}
