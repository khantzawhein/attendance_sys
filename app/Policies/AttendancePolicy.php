<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttendancePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->isTeacher();
    }

    public function updateStatus(User $user)
    {
        return $user->isTeacher();
    }

}
