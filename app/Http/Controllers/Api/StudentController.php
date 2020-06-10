<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudent;
use App\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * @return array
     */
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

    /**
     * @param Student $student
     * @return \Illuminate\Http\Response
     */
    public function approve(Student $student) {
//        $this->authorize('student_manage'); //todo
        $student->user->approve();
        return response(['message' => 'success'], 201);
    }

    /**
     * @param Student $student
     * @return \Illuminate\Http\Response
     */
    public function disapprove(Student $student) {
//        $this->authorize('student_manage'); //todo
        $student->user->disapprove();
        return response(['message' => 'success'], 201);
    }

    /**
     * @param Student $student
     * @param StoreStudent $request
     * @return \Illuminate\Http\Response
     */
    public function update(Student $student, StoreStudent $request) {
        $data = $request->validated();
        $student->update($data);
        return response(['message' => 'success', 201]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Student $student
     * @return void
     * @throws \Exception
     */
    public function destroy(Student $student) {
        $student->delete();
        return response(['message' => 'success', 201]);
    }
}
