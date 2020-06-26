<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
{
protected $guarded = [];
protected $hidden = ['created_at', 'updated_at'];

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }
//    public function attendance() {
//        return $this->hasMany(Attendance::class);
//    }
    public function semester() {
        return $this->belongsTo(Semester::class);
    }
    public function timetable() {
        return $this->hasMany(Timetable::class);
    }
    public function bindToTimetable($timetable)
    {
        return $this->timetable()->save($timetable);
    }

    public function getCourseAttendances()
    {
        return Attendance::whereHas('timetable.course', function($q) {
            $q->where('id', $this->id);
        })->get();
    }



}
