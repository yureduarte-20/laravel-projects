<?php

namespace Database\Seeders;

use App\Enum\RolesEnum;
use App\Enum\Semanas;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AgendasSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dr = User::create([
            'name' => 'Odontologo 1',
            'password' => Hash::make('12345678'),
            'email' => 'odo@gmail.com',
        ]);
        $dr->assignRole(RolesEnum::ODONTOLOGO->name);
        $agenda = $dr->agenda()->create();
        $disponibilidade = $agenda->disponibilidades()->create([ 'disponibilidade_semana' => Semanas::QUI ]);
        $disponibilidade->horarios()->create([ 'horario_inicio' => '08:00:00', 'horario_final' => '12:00:00' ]);
    }
}
