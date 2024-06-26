<?php

namespace Database\Seeders;

use App\Enum\RolesEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::findOrCreate(RolesEnum::ADMIN->name);
        $user = User::create(['name' => 'yure', 'email' => 'yure@gmail.com', 'password' => Hash::make('12345678')]);
        $user->assignRole(RolesEnum::ADMIN->name);
    }
}
