<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Timetable extends Model
{
    protected $guarded = [];
    protected $hidden = ['section_id', 'course_id', 'created_at', 'updated_at'];

    public function attendances() {
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
    public function getAbsentee()
    {
        return Student::whereDoesntHave('attendances', function (Builder $query) {
            $query->where('timetable_id', $this->id);
        })->get();
    }

    public function setAbsentee()
    {
        $absentees = $this->getAbsentee();
        $attendances = collect([]);
        foreach ($absentees as $absentee)
        {
            $attendance = new Attendance([
               'student_id' => $absentee->id,
               'timetable_id' => $this->id,
               'status' => '0',
               'description' => 'Absent'
            ]);
            $attendances->push($attendance);
        }
        return $this->attendance()->saveMany($attendances);
    }
}
