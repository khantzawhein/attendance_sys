<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Student;
use App\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $approved = Student::join('users', 'users.id', 'students.id')->where('approved', 1)->select('students.id', 'users.name', 'users.email', 'students.urn')->get();
        $not_approved = Student::join('users', 'users.id', 'students.id')->where('approved', 0)->select('students.id', 'users.name', 'users.email', 'students.urn')->get();
        $data = [
            'approved' => $approved,
            'not_approved' => $not_approved
        ];
        return $data;
    }
    public function approve(User $user) {
//        $this->authorize('student_manage'); //todo
        $user->approve();
        return response(['message' => 'success'], 201);
    }
    public function disapprove(User $user) {
//        $this->authorize('student_manage'); //todo
        $user->disapprove();
        return response(['message' => 'success'], 201);
    }
}
