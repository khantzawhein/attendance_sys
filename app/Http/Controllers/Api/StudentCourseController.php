<?php

namespace App\Http\Controllers\Api;

use App\Course;
use App\CourseStudent;
use App\Http\Controllers\Controller;
use App\Http\Resources\StudentCourseResource;
use App\Rules\AccessCodeUsed;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StudentCourseController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', CourseStudent::class);
        if(!request()->user()->isSuperAdmin())
        {
            return StudentCourseResource::collection(request()->user()->student->course->load('teacher.user', 'semester.year'));
        }
        $students = Student::with('course')->get();
        $data = collect([]);
        foreach ($students as $student)
        {
            foreach($student->course as $course) {
                $data->push(
                [
                    'student_id' => $student->id,
                    'student_name' => $student->user->name,
                    'batch' => $student->batch,
                    'course_id' => $course->id,
                    'module_no' => $course->module_no,
                    'module_name' => $course->module_name,
                ]
                );
            }

        }
        return $data;
    }
    public function bind(Request $request)
    {
        $this->authorize('bind', CourseStudent::class);
        $data = $request->validate([
           'access_code' => ['required', 'exists:courses,access_code', new AccessCodeUsed(Auth::user()->student)],
        ]);
        $course = Course::where('access_code', $data['access_code'])->first();
        $request->user()->student->bindCourse($course);

        return response('', 201);
    }
    public function unbind(Request $request)
    {
        $this->authorize('unbind', CourseStudent::class);
        $data = $request->validate([
            'student_id' => 'exists:students,id',
            'course_id' => 'required|exists:courses,id',
        ]);
        if(request()->user()->isSuperAdmin)
        {
            $student = Student::find($data['student_id']);
            $course = Course::findOrFail($data[course_id]);
            $student->unbindCourse($course);
        }
        else {
            $course = Course::findOrFail($data[course_id]);
            $request->user()->student->unbindCourse($course);
        }

        return response('', 201);
    }
}
