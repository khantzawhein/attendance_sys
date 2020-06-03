<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    public function course() {
        return $this->belongsTo(Course::class);
    }
    public function student() {
        return $this->belongsTo(Student::class);
    }
    public function timetable() {
        return $this->belongsTo(Timetable::class);
    }
}
