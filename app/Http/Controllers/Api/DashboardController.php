<?php

namespace App\Http\Controllers\Api;

use App\Course;
use App\Http\Controllers\Controller;
use App\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = request()->user();

        $this_month = Carbon::now()->month;
        $last_month = Carbon::now()->subMonth()->month;
        if ($user->isStudent())
        {
            if($user->student->sections->isEmpty())
            {
                return [
                    'courseCount' => 0,
                    'presentRate' => 0,
                    'lastMonthPresentRate' => 0,
                    'attendanceLateCount' => 0
                ];
            }
            return [
                'courseCount' => $this->courseCount(),
                'presentRate' => $this->attendanceRate($this_month),
                'lastMonthPresentRate' => $this->attendanceRate($last_month),
                'attendanceLateCount' => $this->attendanceLateCount()
            ];
        }
        return [
            'courseCount' => $this->courseCount(),
            'pendingStudentCount' => $this->pendingStudentCount(),
            'presentRate' => $this->attendanceRate($this_month),
            'lastMonthPresentRate' => $this->attendanceRate($last_month)
        ];
    }
    public function attendanceLateCount()
    {
        $user = request()->user();
        return $user->student->attendances()->where('status', 2)->count();
    }

    public function courseCount()
    {
        $user = request()->user();
        if($user->isTeacher())
        {
            return $user->teacher->courses->count();
        }
        else if ($user->isSuperAdmin()) {
            return Course::count();
        }
        return $user->student->sections->last()->timetable->map->course->unique()->count();
    }
    public function pendingStudentCount()
    {
        $this->authorize('viewAny', Student::class);
        $pendingStudentCount = Student::join('users', 'users.id', 'students.user_id')->where('approved', 0)->count();
        return $pendingStudentCount;

    }
    public function absentRate($month)
    {
        $user = request()->user();
        if ($user->isTeacher())
        {
            $teacher = $user->teacher;
            $absentCount = $teacher->courses->map->timetable->flatten()->map->attendances()->map->whereMonth('created_at', $month)->map->get()->flatten()->where('status', 0)->count();
            $attendanceCount = $teacher->courses->map->timetable->flatten()->map->attendances()->map->whereMonth('created_at', $month)->map->get()->flatten()->count();
        }
        else if ($user->isSuperAdmin())
        {
            $courses = Course::all();
            $absentCount =  $courses->map->timetable->flatten()->map->attendances()->map->whereMonth('created_at', $month)->map->get()->flatten()->where('status', 0)->count();
            $attendanceCount = $courses->map->timetable->flatten()->map->attendances()->map->whereMonth('created_at', $month)->map->get()->flatten()->count();
        }
        else {
            $absentCount = $user->student->attendances()->where('status', 0)->count();
            $attendanceCount = $user->student->attendances()->count();
        }
        $absentRate = 0;
        if($attendanceCount != 0)
        {
            $absentRate = ($absentCount/$attendanceCount)*100.00;
        }

        return round($absentRate, 1);
    }
    public function attendanceRate($month)
    {
        $user = request()->user();
        if ($user->isTeacher())
        {
            $teacher = $user->teacher;
            $presentCount = $teacher->courses->map->timetable->flatten()->map->attendances()->map->whereMonth('created_at', $month)->map->get()->flatten()->where('status', '>=', 1)->count();
            $attendanceCount = $teacher->courses->map->timetable->flatten()->map->attendances()->map->whereMonth('created_at', $month)->map->get()->flatten()->count();
        }
        else if ($user->isSuperAdmin())
        {
            $courses = Course::all();
            $presentCount =  $courses->map->timetable->flatten()->map->attendances()->map->whereMonth('created_at', $month)->map->get()->flatten()->where('status', '>=', 1)->count();
            $attendanceCount = $courses->map->timetable->flatten()->map->attendances()->map->whereMonth('created_at', $month)->map->get()->flatten()->count();
        }
        else {
            $presentCount = $user->student->attendances()->whereMonth('created_at', $month)->where('status','>=', 1)->count();
            $attendanceCount = $user->student->attendances()->whereMonth('created_at', $month)->count();
        }
        $presentRate = 0;
        if($attendanceCount != 0)
        {
            $presentRate = ($presentCount/$attendanceCount)*100.00;
        }
        return round($presentRate,1);
    }


    }
