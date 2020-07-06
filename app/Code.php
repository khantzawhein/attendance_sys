<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    //
    protected $fillable = [
        'timetable_id','code', 'expire_at'
    ];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function timetable() {
        return $this->belongsTo(Timetable::class);
    }
    public function isExpired()
    {
        $now = Carbon::now();
        return $now->greaterThan(Carbon::parse($this->expire_at));
    }

}
