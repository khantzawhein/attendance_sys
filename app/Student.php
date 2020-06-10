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

}
