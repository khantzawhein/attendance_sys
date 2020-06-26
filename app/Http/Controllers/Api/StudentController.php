<?php

namespace App\Http\Controllers\Api;

use App\Attendance;
use App\Code;
use App\Http\Controllers\Controller;
use App\Rules\AttendanceCodeUsed;
use App\Student;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Student::class, 'student');
    }

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
        $this->authorize('can_approve', $student);
        $student->user->approve();
        return response(['message' => 'success'], 201);
    }

    public function disapprove(Student $student) {
        $this->authorize('can_approve', $student);
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
        $student->user->delete();
        return response(['message' => 'success', 201]);
    }

    public function changePassword(Student $student, Request $request)
    {
        $this->authorize('changePassword', $student);
        $data = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $student->user;
        $user->password = Hash::make($data['password']);
        $user->save();

        return response('' , 201);
    }

    public function getAttendance(Request $request)
    {
        $this->authorize('getAttendance', Student::class);

        $data = $request->validate([
            'code' => ['required', new AttendanceCodeUsed($request->user()->student)],
        ]);

        $code = Code::where('code', $data['code'])->first();

        if (!$code->timetable->section->students->contains($request->user()->student))
        {
            return response(['errors' => [
                'code' => ['You haven\'t enrolled in that class.']
            ]], 422);
        }

        $now = Carbon::now();
        if ($now->greaterThan(Carbon::parse($code->expire_at))) {
            return response(['errors' => [
                'code' => ['Code has expired.']
            ]], 422);
        }

        $semester_start = $code->timetable->section->semester->start_date;
        $semester_end = $code->timetable->section->semester->end_date;
        $start_date = Carbon::parse($semester_start);
        $end_date = Carbon::parse($semester_end);

        if (Carbon::now()->startOfDay()->greaterThan($end_date))
        {
            return response(['errors' => [
                'code' => ['This course\'s semester has finished.']
            ]], 422);
        }

        $week = Carbon::now()->isoWeek() - $start_date->isoWeek();

        $attendance = new Attendance([
            'student_id' => $request->user()->student->id,
            'timetable_id' => $code->timetable->id,
            'week' => $week,
            'status' => 1,
            'description' => 'Present'
        ]);

        $attendance->save();

        return response('', 201);


    }
}
