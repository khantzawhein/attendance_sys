<?php

namespace App\Http\Controllers\Api;

use App\Course;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function rules()
    {
        return [
                'module_no' => ['required',
                    Rule::unique('courses', 'module_no')
                    ->where(function ($query){
                        return $query->where('semester_id', request('semester_id'));
                    })
                ],
                'module_name' => 'required',
                'semester_id' => 'required|exists:semesters,id',
                'teacher_id' => 'required|exists:teachers,id'
           ];
    }
    public function __construct()
    {
        $this->authorizeResource(Course::class, 'course');
    }

    public function index()
    {
        $user = request()->user();
        if($user->isTeacher())
        {
            return CourseResource::collection(Course::where('teacher_id', $user->teacher->id)->get());
        }
        if($user->isStudent())
        {
            return CourseResource::collection(request()->user()->student->getCourses());
        }
    }
    public function allCourses()
    {
        $this->authorize('viewAll', Course::class);
        return CourseResource::collection(Course::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $user = request()->user();
        if(!$user->isSuperAdmin() && $data['teacher_id'] != $user->teacher->id)
        {
            return abort(403, 'This action is not authorized.');
        }
        Course::create($data);
        return response(['message' => 'success'], 201);
    }
    public function show(Course $course)
    {
        return new CourseResource($course);
    }

    public function update(Course $course, Request $request)
    {
        $data = $request->validate([
            'module_no' => ['required',
                    Rule::unique('courses', 'module_no')
                    ->where(function ($query){
                        return $query->where('semester_id', request('semester_id'));
                    })->ignore($course->id)
                ],
                'module_name' => 'required',
                'semester_id' => 'required|exists:semesters,id',
                'teacher_id' => 'required|exists:teachers,id'
        ]);
        $user = request()->user();
        if(!$user->isSuperAdmin() && $data['teacher_id'] != $user->teacher->id)
        {
            return abort(403, 'This action is not authorized.');
        }
        $course->update($data);
        return response(['message' => 'success'], 201);
    }


    public function destroy(Course $course)
    {
        $course->delete();
        return response(['message' => 'success'], 201);
    }


}
