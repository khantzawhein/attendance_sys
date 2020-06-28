<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Section extends Model
{
    protected $guarded = [];
    protected $hidden = ['access_code', 'created_at', 'updated_at'];

    public function timetable()
    {
        return $this->hasMany(Timetable::class);
    }
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
    public function students()
    {
        return $this->belongsToMany(Student::class)->withTimestamps();
    }
    public function resetAccessCode()
    {
        $code = strtolower(Str::random(6));
        $this->access_code = $code;
        return $this->save();
    }
    public function getAccessCode()
    {
        return $this->access_code;
    }
}
