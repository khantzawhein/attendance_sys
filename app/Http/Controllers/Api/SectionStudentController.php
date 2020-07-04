<?php

namespace App\Http\Controllers\Api;

use App\Section;
use App\SectionStudent;
use App\Http\Controllers\Controller;
use App\Http\Resources\StudentClassResource;
use App\Rules\AccessCodeUsed;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SectionStudentController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', SectionStudent::class);
        if(!request()->user()->isSuperAdmin())
        {
            return StudentClassResource::collection(request()->user()->student->sections->load('semester.year'));
        }
        $students = Student::with('sections')->get();
        $data = collect([]);
        foreach ($students as $student)
        {
            foreach($student->sections as $section) {
                $data->push(
                [
                    'student_id' => $student->id,
                    'student_name' => $student->user->name,
                    'batch' => $student->batch,
                    'section_name' => $section->name,
                    'academic_year' => $section->semester->year->academic_year,
                    'year' => $section->semester->year->name,
                    'semester_name' => $section->semester->semester_name,
                ]
                );
            }

        }
        return $data;
    }
    public function bind(Request $request)
    {
        $this->authorize('bind', SectionStudent::class);
        $data = $request->validate([
           'access_code' => ['required', 'exists:sections,access_code', new AccessCodeUsed(Auth::user()->student)],
        ]);
        $section = Section::where('access_code', $data['access_code'])->first();
        $request->user()->student->bindSection($section);

        return response('', 201);
    }
    public function unbind(Section $section, Request $request)
    {
        $this->authorize('unbind', SectionStudent::class);
        $data = $request->validate([
            'student_id' => 'exists:students,id',
        ]);

        $student = Student::find($data['student_id']);
        $student->unbindSection($section);

        return response('', 201);
    }
    //enroll by teacher
    public function enroll(Section $section, Request $request)
    {
        $this->authorize('enroll', SectionStudent::class);
        $data = $request->validate([
            'student_id' => 'exists:students,id',
        ]);
        $student = Student::find($data['student_id']);
        $section->enroll($student);

        return response('', 201);
    }
}
