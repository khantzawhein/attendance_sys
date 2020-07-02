<?php

namespace App\Policies;

use App\SectionStudent;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SectionStudentPolicy
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
     * @param \App\User $user
     * @param SectionStudent $sectionStudent
     * @return mixed
     */
    public function view(User $user, SectionStudent $sectionStudent)
    {
        return $sectionStudent->student_id == $user->student->id;
    }

    public function bind(User $user)
    {
        return $user->isStudent();
    }
    public function unbind(User $user)
    {
        return $user->isTeacher();
    }

    public function enroll(User $user)
    {
        return $user->isTeacher();
    }
}
