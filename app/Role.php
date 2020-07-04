<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function setAsSuperAdmin(User $user)
    {
        Role::find(2)->users()->save($user);
    }
}
