<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Teacher;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
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
        return response(["message" => "success"], 201);
    }

    public function update(Teacher $teacher, Request $request)
    {
        $data = $request->validate($this->rules());
        $teacher->update($data);
        return response(["message" => "success"], 201);
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->delete();
        return response(["message" => "success"], 201);
    }
}
