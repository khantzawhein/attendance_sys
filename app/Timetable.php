<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    public function attendance() {
        return $this->hasMany(Attendance::class);
    }
    public function course() {
        return $this->belongsTo(Course::class);
    }
}
