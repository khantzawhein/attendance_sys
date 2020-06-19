<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseStudent extends Model
{
    protected $guarded = [];
    protected $hidden = ['course_id', 'student_id'];
}
