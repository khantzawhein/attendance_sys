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
        return $this->hasMany(Attendance::class)->latest('week');
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
    public function generateCode($duration = 10)
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
    public function extendCode($duration = 5)
    {
        $expire = Carbon::parse($this->code->expire_at);
        $this->code->expire_at = $expire->addMinute(5);
        return $this->code->save();
    }

    public function getAbsentee()
    {
        return Student::whereDoesntHave('attendances', function (Builder $query) {
            $query->where('timetable_id', $this->id);
        })->get();
    }

    public function setAbsentee()
    {
        $semester_start = $this->section->semester->start_date;
        $start_date = Carbon::parse($semester_start);
        $week = Carbon::now()->isoWeek() - $start_date->isoWeek();
        $absentees = $this->getAbsentee();
        $attendances = collect([]);
        foreach ($absentees as $absentee)
        {
            $attendance = new Attendance([
               'student_id' => $absentee->id,
               'timetable_id' => $this->id,
               'status' => '0',
               'week' => $week,
               'description' => 'Absent',

            ]);
            $attendances->push($attendance);
        }
        return $this->attendances()->saveMany($attendances);
    }
    public function getWeek()
    {
        $semester_start = $this->section->semester->start_date;
        $start_date = Carbon::parse($semester_start);
        return Carbon::now()->diffInWeeks($start_date);
    }

    public function finished_jobs()
    {
        return $this->hasMany(FinishedJob::class);
    }

    public function isCourseFinished()
    {
        $semester_end = $this->section->semester->end_date;
        $end_date = Carbon::parse($semester_end);
        return Carbon::now()->startOfDay()->greaterThan($end_date);
    }
}
