<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Student;
use App\StudentInfoChange;
use App\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required', 'string', 'min:8'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        if (Hash::check($data['current_password'], $request->user()->password))
        {
            $request->user()->update([
                'password' => Hash::make($data['password'])
            ]);
            return response(['message' => 'success'], 201);
        }
        return response(['message' => 'The current password doesn\'t match with our records.'], 422);
    }
    public function teacherInfoGet()
    {
        return Teacher::where('teachers.id',request()->user()->teacher->id)->join('users', 'teachers.user_id', 'users.id')->select('teachers.id', 'users.name', 'users.email', 'teachers.role',  'teachers.department')->first();
    }
    public function teacherInfoEdit(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($request->user()->id)],
            'role' => ['required', 'string'],
            'department' => ['required', 'string', 'max:255']
        ]);

        $request->user()->update([
           'name' => $data['name'],
           'email' => $data['email']
        ]);
        $teacher =  $request->user()->teacher;
        $teacher->role = $data['role'];
        $teacher->department = $data['department'];
        $teacher->save();

        return response(['message' => 'success'], 201);
    }

    public function studentInfoGet()
    {
        $student = Student::where('students.id', request()->user()->student->id)->join('users', 'users.id', 'students.user_id')->select('students.*', 'users.name', 'users.email')->first();
        $studentInfoChange = $student->StudentInfoChange->last();
        if ($studentInfoChange)
        {
            if ($studentInfoChange->approved_at == null)
            {
                return ['isPending' => true, 'data' => $student->StudentInfoChange->last()];
            }

        }
        return ['isPending' => false, 'data' => $student];
    }

    public function studentInfoEdit(Request $request)
    {
        $user = $request->user();
        $student = $user->student;
        $studentInfoChange = $student->StudentInfoChange->last();
        if ($studentInfoChange)
        {
            if ($studentInfoChange->approved_at == null)
            {
                return response(['message' => 'Your previous request is still in progress.'], 422);
            }
        }
        $data = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'urn' => ['digits:6', Rule::unique('students')->ignore($student->id)],
                'nrc' => ['required'],
                'father_name' => ['required', 'string'],
                'batch' => ['required', 'digits:4'],
                'phone' => ['required', 'digits_between:8,11', Rule::unique('students')->ignore($student->id)]
            ]);
        $data['student_id'] = $student->id;
        StudentInfoChange::create($data);
        return response('', 201);

    }

}
