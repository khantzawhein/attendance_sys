<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    //
    protected $fillable = [
        'timetable_id','code', 'expire_at'
    ];
}
