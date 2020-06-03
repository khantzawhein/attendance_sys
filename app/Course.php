<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public function teacher() {
        return $this->hasOne(Teacher::class);
    }
    public function attendance() {
        return $this->hasMany(Attendance::class);
    }
}
