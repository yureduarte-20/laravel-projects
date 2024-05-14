<?php

namespace Database\Seeders;

use App\Enum\RolesEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(RolesEnum::cases() as $role) {
            Role::findOrCreate($role->name);
        }
    }
}
