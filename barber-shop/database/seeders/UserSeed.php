<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL') ?? 'admin@gmail.com',], [
            'email' => env('ADMIN_EMAIL') ?? 'admin@gmail.com',
            'name' => 'admin padrão',
            'role' => 'admin',
            'password' => Hash::make(env('ADMIN_PASSWORD') ?? '12345678')
        ]);
    }
}
