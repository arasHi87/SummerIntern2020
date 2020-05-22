<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('users')->delete();

        User::create([
            'name' => 'User_test',
            'email' => 'User_test@gmail.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_USER,
        ]);

        User::create([
            'name' => 'Admin_test',
            'email' => 'Admin_test@gmail.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
        ]);
    }
}
