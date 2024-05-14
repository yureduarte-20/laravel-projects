<?php

use App\DTOs\AgendamentoDTO;
use App\Enum\RolesEnum;
use App\Enum\Semanas;
use App\Models\Agenda;
use App\Models\AgendaHorario;
use App\Models\Consulta;
use App\Models\Especialidade;
use App\Models\Horario;
use App\Models\User;
use App\Service\AgendamentosService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HorarioTest extends TestCase
{
    use RefreshDatabase;
    public function test_mudanca_horario()
    {
        $this->seed();
        $agenda = Agenda::first();
        $user = User::factory()->create();
        $user->assignRole(RolesEnum::PACIENTE->name);
        $c = Consulta::create([
            'user_id' => $user->id,
            'agenda_id' => $agenda->id,
            'especialidade_id' => Especialidade::first()->id,
            'dia' => '2024-05-12',
            'horario_id' => $agenda->horarios->first()->id,
        ]);
        $antigoHorario = $agenda->horarios()->get();
        $horario_no =  Horario::whereIn('dia_semana', [Semanas::SEX->name, Semanas::SAB->name])
        ->where('horario', '11:00:00')->get()->pluck('id');
        $agenda->horarios()->sync( $horario_no) ;
        $this->assertDatabaseHas(Consulta::class, [
            'id' => $c->id
        ]);
        foreach($antigoHorario as $antigo){
            $this->assertDatabaseMissing(AgendaHorario::class, [
                'id' => $antigo->id
            ]);
        }
    }
}
