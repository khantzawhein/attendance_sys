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

    public function sections()
    {
        return $this->belongsToMany(Section::class)->withTimestamps();
    }
    public function bindSection($section)
    {
        $this->sections()->sync($section, false);
    }
    public function unbindSection($section)
    {
        $this->sections()->detach($section);
    }

    public function getCourses()
    {
        return $this->sections->map->semester->map->course->flatten();
    }
}
