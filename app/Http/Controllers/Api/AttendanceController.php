<?php

namespace App\Http\Controllers\Api;

use App\Attendance;
use App\Course;
use App\Http\Controllers\Controller;
use App\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    //This Controller is for retrieve and showing students' attendances to Teachers

    public function index(Course $course)
    {
        $attendances = $course->getCourseAttendances();
        $data = collect([]);
        foreach ($attendances as $attendance)
        {
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

    public function updateStatus($course_id, Attendance $attendance)
    {
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
