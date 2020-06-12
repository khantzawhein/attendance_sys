<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
{
protected $guarded = [];

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }
    public function attendance() {
        return $this->hasMany(Attendance::class);
    }
    public function term() {
        return $this->belongsTo(Term::class);
    }
    public function timetable() {
        return $this->hasMany(Timetable::class);
    }
    public function resetAccessCode()
    {
        $code = Str::random(6);
        $this->access_code = $code;
        $this->save();
        return true;
    }
    public function getAccessCode()
    {
        return $this->access_code;
    }
}
