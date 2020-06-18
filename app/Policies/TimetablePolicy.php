<?php

namespace App\Policies;

use App\User;
use App\Timetable;
use Illuminate\Auth\Access\HandlesAuthorization;

class TimetablePolicy
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
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\User $user
     * @param Timetable $timetable
     * @return mixed
     */
    public function view(User $user, Timetable $timetable)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isTeacher();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\User $user
     * @param Timetable $timetable
     * @return mixed
     */
    public function update(User $user, Timetable $timetable)
    {
        return $user->isTeacher();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\User $user
     * @param Timetable $timetable
     * @return mixed
     */
    public function delete(User $user, Timetable $timetable)
    {
        return $user->isTeacher();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Timetable  $Timetable
     * @return mixed
     */

}
