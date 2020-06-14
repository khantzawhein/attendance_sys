<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function timetable()
    {
        return $this->hasMany(Timetable::class);
    }
}
