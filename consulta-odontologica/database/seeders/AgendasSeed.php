<?php

namespace Database\Seeders;

use App\Enum\RolesEnum;
use App\Enum\Semanas;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
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
        $disponibilidade = $agenda->disponibilidades()->create(['disponibilidade_semana' => Semanas::QUI]);
        $startTime = Carbon::parse('08:00');
        $endTime = Carbon::parse('12:00');
        $timeRange = [];
        $currentTime = $startTime;
        while ($currentTime <= $endTime) {
            $timeRange[] = $currentTime->toTimeString();
            $currentTime->addMinutes(30);
        }
        foreach($timeRange as $time){
            $disponibilidade->horarios()->create([ 'horario' => $time ]);
        }
    }
}
