<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Role;
use App\Teacher;
use App\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $this->authorize('manageSuperAdmin', Role::class);
        //Superadmin Lists
        return Role::where('role', 'superadmin')->first()->users->except(request()->user()->id);
    }

    public function setAsSuperAdmin(Request $request)
    {
        $this->authorize('manageSuperAdmin', Role::class);
        $data = $request->validate(['user_id' => 'required|exists:users,id']);
        $user = User::find($data['user_id']);
        $user->setAsSuperAdmin();
        return response('', 201);

    }
    public function removeAsSuperAdmin(Request $request)
    {
        $this->authorize('manageSuperAdmin', Role::class);
        $data = $request->validate(['user_id' => 'required|exists:users,id']);
        $user = User::find($data['user_id']);
        $user->removeAsSuperAdmin();
        return response('', 201);

    }
}
