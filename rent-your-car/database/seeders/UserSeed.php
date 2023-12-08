<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'email' => env('ADMIN_EMAIL', 'admin@gmail.com'),
            'password' => env('ADMIN_PASSWORD', '12345678'),
            'role' => 'admin',
            'name' => 'admin'
        ]);
    }
}
