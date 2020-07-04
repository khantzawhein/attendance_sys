<?php

namespace App\Http\Controllers\Api;

use App\Attendance;
use App\Course;
use App\Http\Controllers\Controller;
use App\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    //This Controller is for retrieve and showing students' attendances to Teachers/Students

    public function index(Course $course)
    {
        $this->authorize('viewAny', Attendance::class);
        $attendances = $course->getCourseAttendances();
        $data = collect([]);
        foreach ($attendances as $attendance) {
            $data->push([
                'id' => $attendance->id,
                'module_no' => $course->module_no,
                'module_name' => $course->module_name,
                'day' => $attendance->timetable->day,
                'week' => $attendance->week,
                'student_name' => $attendance->student->user->name,
                'status' => $attendance->status,
                'description' => $attendance->description,
                'date' => $attendance->created_at
            ]);
        }

        return $data;
    }

    public function studentAttendance()
    {

        $this->authorize('getAttendance', Student::class);
        $user = request()->user();
        $attendances =  $user->student->attendances()->take(200)->get()->load('timetable.course');

        $data = collect([]);

        foreach ($attendances as $attendance)
        {
            $data->push([
                'student_name' => $user->name,
                'module_no' => $attendance->timetable->course->module_no,
                'module_name' => $attendance->timetable->course->module_name,
                'status' => $attendance->status,
                'description' => $attendance->description,
                'created_at' => $attendance->created_at,
                'day' => $attendance->timetable->day,
                'week' => $attendance->week
            ]);
        }
        return $data;

    }

    public function updateStatus($course_id, Attendance $attendance)
    {
        $this->authorize('updateStatus', Attendance::class);
        $data = request()->validate([
             'status' => 'required|integer|between:0,3'
        ]);

        $description = ['Absent', 'Present', 'Late', 'Leave'];

        $attendance->status = $data['status'];
        $attendance->description = $description[$data['status']];
        $attendance->save();

        return response('', 201);
    }
}
