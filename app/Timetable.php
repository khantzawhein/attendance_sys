<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
    public function attendance() {
        return $this->hasMany(Attendance::class);
    }
    public function course() {
        return $this->belongsTo(Course::class);
    }
}
