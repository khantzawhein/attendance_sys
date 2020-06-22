<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Timetable extends Model
{
    protected $guarded = [];
    protected $hidden = ['section_id', 'course_id', 'created_at', 'updated_at'];

    public function attendance() {
        return $this->hasMany(Attendance::class);
    }
    public function course() {
        return $this->belongsTo(Course::class);
    }
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
    public function unbindCourse()
    {
        return $this->course()->dissociate()->save();
    }
    public function code()
    {
        return $this->hasOne(Code::class);
    }
    public function generateCode($duration = 15)
    {
        $code = str_pad(rand(10, 999999),6, 0);
        $db = new Code([
            'code' => $code,
            'expire_at' => Carbon::now()->addMinute($duration)
        ]);
        $this->code()->save($db);
        return ['code' => $code, 'expire_at' => $db->expire_at];
    }

    public function revokeCode()
    {
        return $this->code()->delete();
    }
}
