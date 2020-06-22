<?php

namespace App\Policies;

use App\Student;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->isTeacher();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Student  $student
     * @return mixed
     */
    public function view(User $user, Student $student)
    {
        if($student->user->is($user))
        {
            return true;
        }
        return $user->isTeacher();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Student  $student
     * @return mixed
     */
    public function update(User $user, Student $student)
    {
        if($student->user->is($user))
        {
            return true;
        }
        return $user->isTeacher();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Student  $student
     * @return mixed
     */
    public function delete(User $user, Student $student)
    {
        return $user->isSuperAdmin();
    }

    public function can_approve(User $user, Student $student)
    {
        return $user->isTeacher();
    }

    public function changePassword(User $user, Student $student)
    {
        if($student->user->is($user))
        {
            return true;
        }
        return $user->isTeacher();
    }
    public function getAttendance(User $user)
    {
        return $user->isStudent();
    }
}
