<?php

namespace App\Http\Controllers\Api;

use App\Course;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use Illuminate\Http\Request;

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
}
