<?php

namespace App\Policies;

use App\Section;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SectionPolicy
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
     * @param  \App\Section  $section
     * @return mixed
     */
    public function view(User $user, Section $section)
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
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Section  $section
     * @return mixed
     */
    public function update(User $user, Section $section)
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Section  $section
     * @return mixed
     */
    public function delete(User $user, Section $section)
    {
        return $user->isSuperAdmin();
    }

    public function get_students(User $user, Section $section)
    {
        return $user->isTeacher();
    }

    public function manageAccessCode(User $user, Section $section)
    {
        return $user->isTeacher();
    }

}
