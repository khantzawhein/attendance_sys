<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    protected $guarded = [];
    protected $hidden = ['section_id', 'course_id', 'created_at', 'updated_at'];

    public function attendance() {
        return $this->hasMany(Attendance::class);
    }
    public function course() {
        return $this->belongsTo(Course::class);
    }
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
    public function unbindCourse()
    {
        return $this->course()->dissociate()->save();
    }
}
