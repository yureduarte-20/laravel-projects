<?php

namespace Tests\Feature;

use App\DTOs\AgendamentoDTO;
use App\Enum\RolesEnum;
use App\Models\Agenda;
use App\Models\Disponibilidade;
use App\Models\DisponibilidadeHorario;
use App\Models\Especialidade;
use App\Models\User;
use App\Service\AgendamentosService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgendaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_agendas(): void
    {
        $this->seed();
        $user = User::first();
        $this->assertNotNull($user);
        $agenda = Agenda::newModelInstance(['user_id' => $user->id]);
        $agenda->user()->associate($user);
        $agenda->save();
        $this->assertEquals($user->agenda->id, $agenda->id);
    }

    public function test_agendas_disponibilidade(): void
    {
        $this->seed();
        $this->assertDatabaseHas('users', [
            'email' => 'yure@gmail.com',
        ]);
        $user = User::where([
            'email' => 'yure@gmail.com',
        ])->first();
        $agenda = Agenda::newModelInstance(['user_id' => $user->id]);
        $agenda->user()->associate($user);
        $agenda->save();
        $this->assertEquals($user->agenda->id, $agenda->id);

    }

    public function test_agendar()
    {
        $this->seed();
        $paciente = User::factory()->create();
        $paciente->assignRole(RolesEnum::PACIENTE->name);

        $agendamento = new AgendamentoDTO([
            'user_id' => $paciente->id,
            'agenda_id' => User::first()->id,
            'especialidade_id' => Especialidade::first()->id,
            'dia' => '24-04-2024 09:00',
        ]);
        $this->app->bind(AgendamentosService::class, fn () => new AgendamentosService);
        $agendamentosService = $this->app->get(AgendamentosService::class);
        $consulta = $agendamentosService->agendar($agendamento);
        $this->assertEquals($paciente->id, $consulta->user_id);
    }
}
