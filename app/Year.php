<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function semesters()
    {
        return $this->hasMany(Semester::class);
    }
}
