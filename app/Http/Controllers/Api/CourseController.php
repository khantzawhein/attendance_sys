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
    public function index()
    {
        return CourseResource::collection(Course::all());
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
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'module_no' => ['required',
                    Rule::unique('courses', 'module_no')
                    ->where(function ($query){
                        return $query->where('term_id', request('term_id'));
                    })
                ],
                'module_name' => 'required',
                'term_id' => 'required|exists:terms,id',
                'teacher_id' => 'required|exists:teachers,id'
           ]);
        $data['access_code'] = Str::random(6);
        Course::create($data);
        return response(['message' => 'success'], 201);
    }
}
