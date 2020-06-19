<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['urn', 'nrc', 'phone', 'father_name', 'batch'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function attendance() {
        return $this->hasMany(Attendance::class);
    }

    public function course()
    {
        return $this->belongsToMany(Course::class)->withTimestamps();
    }
    public function bindCourse($course)
    {
        $this->course()->sync($course, false);
    }
    public function unbindCourse($course)
    {
        $this->course()->detach($course);
    }
}
