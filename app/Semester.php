<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
    public function course()
    {
        $this->hasMany(Course::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }
    public function year()
    {
        return $this->belongsTo(Year::class);
    }
}
