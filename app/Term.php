<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
    public function course()
    {
        $this->hasMany(Course::class);
    }
}
