<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Teacher;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Teacher::class, 'teacher');
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string'],
            'department' => ['required', 'string', 'max:255']
        ];
    }
    public function index()
    {
        $user = request()->user();
        if(!$user->isSuperAdmin())
        {
            return Teacher::where('user_id', $user->id)->join('users', 'teachers.user_id', 'users.id')->select('teachers.id', 'users.name', 'users.email', 'teachers.role',  'teachers.department')->get();
        }
        return Teacher::join('users', 'teachers.user_id', 'users.id')->select('teachers.id', 'users.name', 'users.email', 'teachers.role',  'teachers.department')->get();
    }
    public function store()
    {
        $data = request()->validate($this->rules());
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'approved' => true,
        ]);
        $teacher = new Teacher([
            'role' => $data['role'],
            'department' => $data['department']
        ]);
        $user->teacher()->save($teacher);
        $user->assignRole('teacher');
        return response(["message" => "success"], 201);
    }
    public function show(Teacher $teacher)
    {
        $teacher_db = Teacher::where('teachers.id',$teacher->id)->join('users', 'teachers.user_id', 'users.id')->select('teachers.id', 'users.name', 'users.email', 'teachers.role',  'teachers.department', 'users.id AS user_id')->get();
        return [
            'data' => $teacher_db,
            'isSuperAdmin' => Teacher::find($teacher->id)->user->isSuperAdmin()
        ];
    }
    public function update(Teacher $teacher ,Request $request)
    {
        $user = User::find($teacher->user_id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['string', 'min:8', 'confirmed'],
            'role' => ['required', 'string'],
            'department' => ['required', 'string', 'max:255']
        ]);
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
        $teacher->role = $data['role'];
        $teacher->department = $data['department'];
        $user->teacher()->save($teacher);

        return response(["message" => "success"], 201);
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->user->delete();
        return response(["message" => "success"], 201);
    }

    public function changePassword(Teacher $teacher, Request $request)
    {
        $this->authorize('changePassword', $teacher);
        $data = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $teacher->user;
        $user->password = Hash::make($data['password']);
        $user->save();

        return response('' , 201);
    }
}
