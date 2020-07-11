<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
    public function getTeacherTimetable()
    {
        return $this->courses->map->timetable->map->load('course')->flatten()->where('course.semester.end_date' , '>=', Carbon::now()->format('Y-m-d'));
    }
}
