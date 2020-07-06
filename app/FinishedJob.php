<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FinishedJob extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    public function timetable()
    {
        return $this->belongsTo(Timetable::class);
    }
}
