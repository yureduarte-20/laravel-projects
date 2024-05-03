<?php

namespace Database\Seeders;

use App\Enum\RolesEnum;
use App\Enum\Semanas;
use App\Models\Disponibilidade;
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

        $startTime = Carbon::parse('08:00');
        $endTime = Carbon::parse('18:00');
        $timeRange = [];
        $currentTime = $startTime;
        while ($currentTime <= $endTime) {
            $timeRange[] = $currentTime->toTimeString();
            $currentTime->addMinutes(30);
        }
        foreach (Semanas::cases() as $semana) {
            foreach ($timeRange as $time) {
                Disponibilidade::create([
                    'dia_semana' => $semana,
                    'horario' => $time,
                ]);
            }
        }
        $ag = Disponibilidade::whereIn('dia_semana', [Semanas::SEG->name, Semanas::TER->name])
            ->whereBetween('horario', ['08:00:00', '09:00:00'])->get();
        $agenda->disponibilidade()->saveMany($ag);

    }
}
