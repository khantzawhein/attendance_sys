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
        'password', 'remember_token',
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
        return $this->belongsToMany(Role::class)->withTimestamps();
    }
    public function assignRole($role)
    {
        if (is_string($role))
        {
            $role = Role::whereName($role)->firstOrFail();
        }
        $this->roles()->sync($role, false);
    }
    public function abilities()
    {
         return $this->roles->map->abilities->flatten()->pluck('name')->unique();
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
}
