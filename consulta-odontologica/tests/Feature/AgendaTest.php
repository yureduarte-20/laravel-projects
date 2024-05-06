<?php

namespace Tests\Feature;

use App\DTOs\AgendamentoDTO;
use App\Enum\RolesEnum;
use App\Enum\Semanas;
use App\Enum\StatusConsulta;
use App\Exceptions\AgendamentoDuplicadoException;
use App\Exceptions\OdontologoSemEspecialidadeException;
use App\Exceptions\SemHorariosDisponivelException;
use App\Models\Agenda;
use App\Models\Especialidade;
use App\Models\Horario;
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

    public function test_agendar_com_horario_indisponivel()
    {
        $this->seed();
        $paciente = User::factory()->create();
        $paciente->assignRole(RolesEnum::PACIENTE->name);
        $agenda = Agenda::first();
        $agendamento = new AgendamentoDTO([
            'user_id' => $paciente->id,
            'agenda_id' => $agenda->id,
            'especialidade_id' => $agenda->user->especialidades[0]->id,
            'horario_id' => Horario::whereDoesntHave('agendas', fn ($query) => $query->where('agendas.id', $agenda->id))->first()->id,
            'dia' => '2024-04-24',
        ]);
        $this->app->bind(AgendamentosService::class, fn () => new AgendamentosService);
        $agendamentosService = $this->app->get(AgendamentosService::class);
        $this->assertThrows(fn () => $agendamentosService->agendar($agendamento), SemHorariosDisponivelException::class);
    }

    public function test_agendar_com_doutor_sem_especialidade()
    {
        $this->seed();
        $paciente = User::factory()->create();
        $paciente->assignRole(RolesEnum::PACIENTE->name);
        $agenda = Agenda::first();
        $doutor = $agenda->user;
        $especialidade = Especialidade::whereDoesntHave('users', fn ($query) => $query->where('users.id', $doutor->id))->first();
        $agendamento = new AgendamentoDTO([
            'user_id' => $paciente->id,
            'agenda_id' => $agenda->id,
            'especialidade_id' => $especialidade->id,
            'horario_id' => $agenda->horarios()->first()->id,
            'dia' => '2024-04-24',
        ]);

        $this->app->bind(AgendamentosService::class, fn () => new AgendamentosService);
        $agendamentosService = $this->app->get(AgendamentosService::class);
        $this->assertThrows(fn () => $agendamentosService->agendar($agendamento), OdontologoSemEspecialidadeException::class);
    }

    public function test_agendar_em_horario_com_data_incorreta()
    {
        $this->seed();
        $paciente = User::factory()->create();
        $paciente->assignRole(RolesEnum::PACIENTE->name);
        $agenda = Agenda::first();
        $doutor = $agenda->user;
        $especialidade = Especialidade::whereHas('users', fn ($query) => $query->where('users.id', $doutor->id))->first();
        $agendamento = new AgendamentoDTO([
            'user_id' => $paciente->id,
            'agenda_id' => $agenda->id,
            'especialidade_id' => $especialidade->id,
            'horario_id' => $agenda->horarios()->where('dia_semana', Semanas::SEG->name)->first()->id,
            'dia' => '2024-04-24',
        ]);
        $this->app->bind(AgendamentosService::class, fn () => new AgendamentosService);
        $agendamentosService = $this->app->get(AgendamentosService::class);
        $this->assertThrows(fn () => $agendamentosService->agendar($agendamento), SemHorariosDisponivelException::class);
    }

    public function test_agendar_em_horario_com_data_correta()
    {
        $this->seed();
        $paciente = User::factory()->create();
        $paciente->assignRole(RolesEnum::PACIENTE->name);
        $agenda = Agenda::first();
        $doutor = $agenda->user;
        $especialidade = Especialidade::whereHas('users', fn ($query) => $query->where('users.id', $doutor->id))->first();
        $agendamento = new AgendamentoDTO([
            'user_id' => $paciente->id,
            'agenda_id' => $agenda->id,
            'especialidade_id' => $especialidade->id,
            'horario_id' => $agenda->horarios()->where('dia_semana', Semanas::SEG->name)->first()->id,
            'dia' => '2024-04-22',
        ]);
        $this->app->bind(AgendamentosService::class, fn () => new AgendamentosService);
        $agendamentosService = $this->app->get(AgendamentosService::class);
        $consulta = $agendamentosService->agendar($agendamento);
        $this->assertEquals($consulta->user_id, $paciente->id);
        $this->assertEquals($consulta->especialidade_id, $especialidade->id);
        $this->assertEquals($consulta->agenda_id, $agenda->id);
        $this->assertEquals($consulta->dia->toDateString(), $agendamento->dia);
        $this->assertEquals($consulta->horario_id, $agendamento->horario_id);
        $this->assertEquals($consulta->status, StatusConsulta::AGENDADO);
    }

    public function test_agendar_horario_ja_reservado()
    {
        $this->seed();
        $paciente = User::factory()->create();
        $paciente->assignRole(RolesEnum::PACIENTE->name);
        $agenda = Agenda::first();
        $doutor = $agenda->user;
        $especialidade = Especialidade::whereHas('users', fn ($query) => $query->where('users.id', $doutor->id))->first();
        $dia = $agenda->horarios()->where('dia_semana', Semanas::SEG->name)->first();
        $agendamento = new AgendamentoDTO([
            'user_id' => $paciente->id,
            'agenda_id' => $agenda->id,
            'especialidade_id' => $especialidade->id,
            'horario_id' => $dia->id,
            'dia' => '2024-04-22',
        ]);
        $this->app->bind(AgendamentosService::class, fn () => new AgendamentosService);
        $agendamentosService = $this->app->get(AgendamentosService::class);
        $consulta = $agendamentosService->agendar($agendamento);
        $this->assertEquals($consulta->user_id, $paciente->id);
        $this->assertEquals($consulta->especialidade_id, $especialidade->id);
        $this->assertEquals($consulta->agenda_id, $agenda->id);
        $this->assertEquals($consulta->dia->toDateString(), $agendamento->dia);
        $this->assertEquals($consulta->horario_id, $agendamento->horario_id);
        $this->assertEquals($consulta->status, StatusConsulta::AGENDADO);

        $paciente = User::factory()->create();
        $paciente->assignRole(RolesEnum::PACIENTE->name);

        $agendamento = new AgendamentoDTO([
            'user_id' => $paciente->id,
            'agenda_id' => $agenda->id,
            'especialidade_id' => $especialidade->id,
            'horario_id' => $dia->id,
            'dia' => '2024-04-22',
        ]);
        $this->assertThrows(fn () => $agendamentosService->agendar($agendamento), SemHorariosDisponivelException::class, 'Já existe uma consulta para esse dia, escolha outro horário.');
    }

    public function test_agendar_horario_ja_reservado_com_especialidade_diferente()
    {
        $this->seed();
        $paciente = User::factory()->create();
        $paciente->assignRole(RolesEnum::PACIENTE->name);
        $agenda = Agenda::first();
        $doutor = $agenda->user;
        $especialidade = Especialidade::whereHas('users', fn ($query) => $query->where('users.id', $doutor->id))->first();
        $dia = $agenda->horarios()->where('dia_semana', Semanas::SEG->name)->first();
        $agendamento = new AgendamentoDTO([
            'user_id' => $paciente->id,
            'agenda_id' => $agenda->id,
            'especialidade_id' => $especialidade->id,
            'horario_id' => $dia->id,
            'dia' => '2024-04-22',
        ]);
        $this->app->bind(AgendamentosService::class, fn () => new AgendamentosService);
        $agendamentosService = $this->app->get(AgendamentosService::class);
        $consulta = $agendamentosService->agendar($agendamento);
        $this->assertEquals($consulta->user_id, $paciente->id);
        $this->assertEquals($consulta->especialidade_id, $especialidade->id);
        $this->assertEquals($consulta->agenda_id, $agenda->id);
        $this->assertEquals($consulta->dia->toDateString(), $agendamento->dia);
        $this->assertEquals($consulta->horario_id, $agendamento->horario_id);
        $this->assertEquals($consulta->status, StatusConsulta::AGENDADO);

        $paciente = User::factory()->create();
        $paciente->assignRole(RolesEnum::PACIENTE->name);
        $especialidade = Especialidade::whereHas('users', fn ($query) => $query->where('users.id', $doutor->id))
            ->whereNotIn('especialidades.id', [$especialidade->id])->first();
        $agendamento = new AgendamentoDTO([
            'user_id' => $paciente->id,
            'agenda_id' => $agenda->id,
            'especialidade_id' => $especialidade->id,
            'horario_id' => $dia->id,
            'dia' => '2024-04-22',
        ]);
        $this->assertThrows(fn () => $agendamentosService->agendar($agendamento), SemHorariosDisponivelException::class, 'Já existe uma consulta para esse dia, escolha outro horário.');
    }

    public function test_agendar_com_agendamento_anterior_nao_finalizado()
    {
        $this->seed();
        $paciente = User::factory()->create();
        $paciente->assignRole(RolesEnum::PACIENTE->name);
        $agenda = Agenda::first();
        $doutor = $agenda->user;
        $especialidade = Especialidade::whereHas('users', fn ($query) => $query->where('users.id', $doutor->id))->first();
        $dia = $agenda->horarios()->where('dia_semana', Semanas::SEG->name)->first();
        $agendamento = new AgendamentoDTO([
            'user_id' => $paciente->id,
            'agenda_id' => $agenda->id,
            'especialidade_id' => $especialidade->id,
            'horario_id' => $dia->id,
            'dia' => '2024-04-22',
        ]);
        $this->app->bind(AgendamentosService::class, fn () => new AgendamentosService);
        $agendamentosService = $this->app->get(AgendamentosService::class);
        $consulta = $agendamentosService->agendar($agendamento);
        $this->assertEquals($consulta->user_id, $paciente->id);
        $this->assertEquals($consulta->especialidade_id, $especialidade->id);
        $this->assertEquals($consulta->agenda_id, $agenda->id);
        $this->assertEquals($consulta->dia->toDateString(), $agendamento->dia);
        $this->assertEquals($consulta->horario_id, $agendamento->horario_id);
        $this->assertEquals($consulta->status, StatusConsulta::AGENDADO);
        $dia = $agenda->horarios()->where('dia_semana', Semanas::TER->name)->first();
        $agendamento = new AgendamentoDTO([
            'user_id' => $paciente->id,
            'agenda_id' => $agenda->id,
            'especialidade_id' => $especialidade->id,
            'horario_id' => $dia->id,
            'dia' => '2024-04-23',
        ]);
        $this->app->bind(AgendamentosService::class, fn () => new AgendamentosService);
        $agendamentosService = $this->app->get(AgendamentosService::class);
        $this->assertThrows(fn() => $agendamentosService->agendar($agendamento), AgendamentoDuplicadoException::class);

        $consulta->status = StatusConsulta::ADIADA;
        $consulta->save();
        $this->assertThrows(fn() => $agendamentosService->agendar($agendamento), AgendamentoDuplicadoException::class);
        
        $agendamento->especialidade_id = Especialidade::whereNotIn('especialidades.id', [$agendamento->especialidade_id]) ->whereHas('users', fn ($query) => $query->where('users.id', $doutor->id) )->first()->id;
        $this->assertThrows(fn() => $agendamentosService->agendar($agendamento), AgendamentoDuplicadoException::class);

    }
}
