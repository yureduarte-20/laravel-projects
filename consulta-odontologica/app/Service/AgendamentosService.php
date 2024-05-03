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
        if (!$paciente->is_paciente()) {
            throw new AgendamentoUsuarioInvalidoException();
        }
        $agenda = Agenda::find($agendamento->agenda_id);
        
        $dia = Carbon::parse($agendamento->horario)->setTimezone(new \DateTimeZone('America/Santarem'));
        $dia = $dia->locale('pt_BR');
        $dias = Semanas::cases();
        $dia_semana = $dias[$dia->dayOfWeek];
        
        if($agenda->disponibilidade()->where([ 'dia_semana' => $dia_semana->name,  ])->exists() )
        
    }
}
