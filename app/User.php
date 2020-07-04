<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'approved'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at', 'email_verified_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    public function role_name()
    {
        return $this->roles->pluck('name');
    }
    public function role_label()
    {
        return $this->roles->pluck('label');
    }

    public function assignRole($role)
    {
        if (is_string($role))
        {
            $role = Role::whereName($role)->firstOrFail();
        }
        $this->roles()->sync($role, false);
    }

    public function teacher() {
        return $this->hasOne(Teacher::class);
    }
    public function student() {
        return $this->hasOne(Student::class);
    }
    public function approve() {
        $this->approved = 1;
        $this->save();
    }
    public function disapprove() {
        $this->approved = 0;
        $this->save();
    }

    public function setAsSuperAdmin()
    {
        return $this->roles()->sync(Role::find(1), false);
    }
    public function removeAsSuperAdmin()
    {
        return $this->roles()->detach(Role::find(1));
    }
    public function isSuperAdmin() {
        return $this->role_name()->contains('superadmin');
    }
    public function isTeacher() {
        return $this->role_name()->contains('teacher');
    }
    public function isStudent() {
        return $this->role_name()->contains('student');
    }
}
