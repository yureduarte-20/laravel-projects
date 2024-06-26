<?php

namespace App\Service;

use App\DTOs\AgendamentoDTO;
use App\Enum\Semanas;
use App\Enum\StatusConsulta;
use App\Exceptions\AgendamentoDuplicadoException;
use App\Exceptions\AgendamentoUsuarioInvalidoException;
use App\Exceptions\OdontologoSemEspecialidadeException;
use App\Exceptions\SemHorariosDisponivelException;
use App\Models\Agenda;
use App\Models\Consulta;
use App\Models\Horario;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class AgendamentosService
{
    public function getConsultasEmAberto(int $agenda_id = null) : Collection
    {
        return Consulta::when($agenda_id, fn($query) => $query->where('agenda_id', $agenda_id))
        ->with(['user', 'horario'])
        ->whereIn('status', [ StatusConsulta::AGENDADO->name, StatusConsulta::ADIADA->name ])->get();
    }

    public function agendar(AgendamentoDTO $agendamento): Consulta
    {
        $paciente = User::find($agendamento->user_id);
        if (! $paciente->is_paciente()) {
            throw new AgendamentoUsuarioInvalidoException();
        }
        $agenda = Agenda::find($agendamento->agenda_id);
        $dia = Carbon::createFromFormat('Y-m-d', $agendamento->dia);

        $dias = Semanas::cases();
        $dia_semana = $dias[$dia->dayOfWeek]->name;

        if (! $agenda->horarios()->where(['horario_id' => $agendamento->horario_id])->exists()) {
            throw new SemHorariosDisponivelException("Odontólogo não possui horário disponível para esse dia.");
        }
        if (Consulta::where(['horario_id' => $agendamento->horario_id,
            'agenda_id' => $agendamento->agenda_id,
        ])
            ->whereDate('dia', $agendamento->dia)
            ->exists()) {
            throw new SemHorariosDisponivelException('Já existe uma consulta para esse dia, escolha outro horário.');
        }
        if (! $agenda->user->especialidades()->where('especialidades.id', '=', $agendamento->especialidade_id)->exists()) {
            throw new OdontologoSemEspecialidadeException();
        }
        $dia_atendimento = Horario::find($agendamento->horario_id);
        if ($dia_atendimento->dia_semana != $dia_semana) {
            throw new SemHorariosDisponivelException("O dia agendado não condiz com o dia da semana, {$agendamento->dia} é {$dia->dayOfWeek} {$dia_semana} e o horário solicitado é para {$dia_atendimento->dia_semana}");
        }
        if($paciente->consultas()->whereIn('status', [StatusConsulta::AGENDADO->name, StatusConsulta::ADIADA->name] )->exists()){
            throw new AgendamentoDuplicadoException("Você tem consultas pendentes, portanto não pode realizar outra sem finalizar a anterior");
        }
        return Consulta::create([
            'dia' => $agendamento->dia,
            'horario_id' => $agendamento->horario_id,
            'especialidade_id' => $agendamento->especialidade_id,
            'agenda_id' => $agenda->id,
            'user_id' => $paciente->id,
            'status' => StatusConsulta::AGENDADO,
        ]);

    }
}
