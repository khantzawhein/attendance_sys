<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $approved = Student::join('users', 'users.id', 'students.user_id')->where('approved', 1)->select('students.*', 'users.name', 'users.email')->get();
        $not_approved = Student::join('users', 'users.id', 'students.user_id')->where('approved', 0)->select('students.*', 'users.name', 'users.email')->get();
        $data = [
            'approved' => $approved,
            'not_approved' => $not_approved
        ];
        return $data;
    }
    public function approve(Student $student) {
//        $this->authorize('student_manage'); //todo
        $student->user->approve();
        return response(['message' => 'success'], 201);
    }
    public function disapprove(Student $student) {
//        $this->authorize('student_manage'); //todo
        $student->user->disapprove();
        return response(['message' => 'success'], 201);
    }
}
