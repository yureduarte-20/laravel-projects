<?php

namespace App\Service;

use App\DTOs\AgendamentoDTO;
use App\Enum\Semanas;
use App\Exceptions\AgendamentoUsuarioInvalidoException;
use App\Exceptions\SemHorariosDisponivelException;
use App\Models\Agenda;
use App\Models\Consulta;
use App\Models\Disponibilidade;
use App\Models\DisponibilidadeHorario;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

class AgendamentosService
{
    public function getAgendamentos($user_id)
    {
        // TODO: Implementar getAgendamentos
    }

    public function agendar(AgendamentoDTO $agendamento)
    {
        $paciente = User::find($agendamento->user_id);
        $agenda = Agenda::find($agendamento->agenda_id);
        if (!$paciente->is_paciente()) {
            throw new AgendamentoUsuarioInvalidoException();
        }
        $dia = Carbon::parse($agendamento->horario)->setTimezone(new \DateTimeZone('America/Santarem'));
        $dia = $dia->locale('pt_BR');
        $dias = Semanas::cases();

        $dia_semana = $dias[$dia->dayOfWeek];
        $hora = $dia->format('H') . ':00:00';

        // se o doutor atende nesse dia da semana
        $exist = $agenda->whereHas('disponibilidades', function ($query) use ($hora, $dia_semana, $agendamento) {
            $query->where('disponibilidade_semana', $dia_semana->name)
                ->whereHas(
                    'horarios',
                    fn($query) => $query
                        ->where('disponibilidade_horarios.id', '=', $agendamento->disponibilidade_horario_id)
                );
        })->exists();
        if (!$exist)
            throw new SemHorariosDisponivelException();
        // se alguem já tem atendimento
        $dia_semana = Disponibilidade::where('disponibilidade_semana', $dia_semana->name)->first();
        $exist = Consulta::where(['disponibilidade_horario' => $agendamento->disponibilidade_horario_id])
            ->whereDate('horario', $dia->format('Y-m-d'))
            ->exists();
        if ($exist) throw new SemHorariosDisponivelException("Não é possível agendar nesse horário");
        $consulta = $paciente->consultas()->create([ 'horario' => $dia,
        'agenda_id' => $agendamento->agenda_id ,
        'disponibilidade_horario_id' => $agendamento->disponibilidade_horario_id,
        'especialidade_id' => $agendamento->especialidade_id ]);
        return $consulta;
    }
}
