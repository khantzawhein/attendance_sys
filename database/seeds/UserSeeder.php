<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        DB::table('users')->updateOrInsert([
            'email' => 'admin@gmail.com'
        ],
            [
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('11111111'),
            'approved' => 1
        ]);
        User::where('email', 'admin@gmail.com')->first()->assignRole('superadmin');
        User::where('email', 'admin@gmail.com')->first()->assignRole('teacher');
    }
}
