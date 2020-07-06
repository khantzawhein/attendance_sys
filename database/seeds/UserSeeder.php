<?php

use App\Teacher;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrInsert([
            'email' => 'admin@gmail.com'
        ],
            [
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('11111111'),
            'approved' => 1
        ]);
        $user = User::where('email', 'admin@gmail.com')->first();
        $user->assignRole('superadmin');
        $user->assignRole('teacher');
        $teacher = new Teacher([
           'role' => 'Admin',
           'department' => "Admin Dept"
        ]);
        $user->teacher()->save($teacher);
    }
}
