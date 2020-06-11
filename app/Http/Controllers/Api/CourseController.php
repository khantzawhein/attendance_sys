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
                        return $query->where('term_id', request('term_id'));
                    })
                ],
                'module_name' => 'required',
                'term_id' => 'required|exists:terms,id',
                'teacher_id' => 'required|exists:teachers,id'
           ];
    }

    public function index()
    {
        return CourseResource::collection(Course::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['access_code'] = Str::random(6);
        Course::create($data);
        return response(['message' => 'success'], 201);
    }
    public function show(Course $course)
    {
        return new CourseResource($course);
    }

    public function update(Course $course, Request $request)
    {
        $data = $request->validate($this->rules());
        $course->update($data);
        return response(['message' => 'success'], 201);
    }


    public function destroy(Course $course)
    {
        $course->delete();
        return response(['message' => 'success'], 201);
    }

    public function resetAccessCode(Course $course)
    {
        $course->resetAccessCode();
        return response('', 201);
    }
    public function getAccessCode(Course $course)
    {
        return response(['access_code' => $course->getAccessCode()], 200);
    }
}
