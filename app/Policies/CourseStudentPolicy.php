<?php

namespace App\Policies;

use App\CourseStudent;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CourseStudentPolicy
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
        return $user->isStudent();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\CourseStudent  $courseStudent
     * @return mixed
     */
    public function view(User $user, CourseStudent $courseStudent)
    {
        return $courseStudent->user_id == $user->id;
    }

    public function bind(User $user)
    {
        return $user->isStudent();
    }
    public function unbind(User $user)
    {
        return $user->isStudent();
    }
}
