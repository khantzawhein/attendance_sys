<?php

namespace App\Policies;

use App\StudentInfoChange;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentInfoChangePolicy
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
     * @param  \App\StudentInfoChange  $studentInfoChange
     * @return mixed
     */
    public function view(User $user, StudentInfoChange $studentInfoChange)
    {
        return $user->isTeacher();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\StudentInfoChange  $studentInfoChange
     * @return mixed
     */
    public function update(User $user, StudentInfoChange $studentInfoChange)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\StudentInfoChange  $studentInfoChange
     * @return mixed
     */
    public function delete(User $user, StudentInfoChange $studentInfoChange)
    {
        return $user->isTeacher();
    }

    public function approveRequest(User $user, StudentInfoChange $studentInfoChange)
    {
        return $user->isTeacher();
    }
}
