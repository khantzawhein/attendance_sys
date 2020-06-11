<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudent;
use App\Student;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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

    public function disapprove(Student $student) {
//        $this->authorize('student_manage'); //todo
        $student->user->disapprove();
        return response(['message' => 'success'], 201);
    }

    public function show(Student $student) {
        $student = Student::where('students.id', $student->id)->join('users', 'users.id', 'students.user_id')->select('students.*', 'users.name', 'users.email')->get();
        return $student;
    }
    public function update(Student $student, Request $request) {
        $user = User::find($student->user_id);
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
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        $student->fill([
            'urn' => isset($data['urn']) ? $data['urn'] : null,
            'nrc' => $data['nrc'],
            'batch' => $data['batch'],
            'father_name' => $data['father_name'],
            'phone' => $data['phone']
        ]);
        $user->student()->save($student);

        return response('', 201);
    }

    public function destroy(Student $student) {
        $student->delete();
        return response(['message' => 'success', 201]);
    }

    public function changePassword(Student $student, Request $request)
    {
        $data = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $student->user;
        $user->password = Hash::make($data['password']);
        $user->save();

        return response('' , 201);
    }
}
