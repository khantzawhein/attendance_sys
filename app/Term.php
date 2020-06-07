<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    protected $guarded = [];
    public function course()
    {
        $this->hasMany(Course::class);
    }
}
