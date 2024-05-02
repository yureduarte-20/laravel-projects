<?php

namespace App\Service;

use App\DTOs\AgendamentoDTO;
use App\Enum\Semanas;
use App\Exceptions\AgendamentoUsuarioInvalidoException;
use App\Exceptions\SemHorariosDisponivelException;
use App\Models\Agenda;
use App\Models\User;
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
        if (! $paciente->is_paciente()) {
            throw new AgendamentoUsuarioInvalidoException();
        }
        $dia = Carbon::parse($agendamento->horario)->setTimezone(new \DateTimeZone('America/Santarem'));
        $dia = $dia->locale('pt_BR');
        $dias = Semanas::cases();
        
        $dia_semana = $dias[$dia->dayOfWeek];
        $hora = $dia->format('H:i:s');
        $exist = $agenda->whereHas('disponibilidades', function ($query) use ($hora, $dia_semana) {
            $query->where('disponibilidade_semana', $dia_semana->name)
                ->whereHas('horarios', fn($query) => $query
                    ->whereTime('horario_inicio', '<=', $hora)
                    ->whereTime('horario_final', '>=', $hora)
                );
        })->exists();
       if(!$exist) throw new SemHorariosDisponivelException();
       
    }
}
