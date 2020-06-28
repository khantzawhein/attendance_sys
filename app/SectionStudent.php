<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SectionStudent extends Model
{
    protected $guarded = [];
    protected $hidden = ['section_id', 'student_id'];
}
