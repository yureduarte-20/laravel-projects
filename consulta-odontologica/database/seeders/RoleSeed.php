<?php

namespace Database\Seeders;

use App\Enum\RolesEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::findOrCreate(RolesEnum::ADMIN->name);
        Role::findOrCreate(RolesEnum::ODONTOLOGO->name);
        Role::findOrCreate(RolesEnum::PACIENTE->name);
    }
}
