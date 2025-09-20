<?php
// File: database/seeders/UserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'Super Administrator',
                'username' => 'superadmin',
                'password' => Hash::make('admin123'),
                'role' => 'super_admin',
            ],
            [
                'name' => 'Kasir Hotel',
                'username' => 'kasir',
                'password' => Hash::make('kasir123'),
                'role' => 'kasir',
            ],
            [
                'name' => 'Cleaning Service',
                'username' => 'cleaning',
                'password' => Hash::make('clean123'),
                'role' => 'cleaning',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
