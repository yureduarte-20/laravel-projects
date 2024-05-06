<?php

namespace App\Http\Controllers\Api;

use App\DTOs\AgendamentoDTO;
use App\Enum\Semanas;
use App\Http\Controllers\Controller;
use App\Models\Especialidade;
use App\Models\Horario;
use App\Service\AgendamentosService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AgendamentoController extends Controller
{
    public function __construct(
        private AgendamentosService $agendamentosService
    ) {

    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'agenda_id' => 'required|exists:agendas,id',
            'dia' => 'required|date',
            'especialidade' => 'required|exists:especialidades,nome',
        ], [
            'agenda_id.exists' => 'Não existe esta agenda.',
            'agenda_id.required' => 'Agenda é obrigatório.',
            'dia.date' => 'Data em formato inválido.',
            'dia.required' => 'o campo "dia" é obrigatória.',
            'especialidade.required' => 'Especialidade é obrigatória',
            'especialidade.exists' => 'Não existe essa especialidade.' 
        ]);
        $dia = Carbon::parse($validated['dia'], new \DateTimeZone('America/Santarem'))->locale('pt_BR')->ceilHours(1);
        $horario = $dia->format('H:i:s');
        $dia_semana = $dia->dayOfWeek;
        $horario_id = Horario::where('dia_semana', Semanas::cases()[$dia_semana]->name)
            ->whereTime('horario', $horario)->firstOrFail();
        $agendamento = new AgendamentoDTO([
            'user_id' => $request->user()->id,
            'horario_id' => $horario_id->id,
            'agenda_id' => $validated['agenda_id'],
            'especialidade_id' => Especialidade::where('nome', $validated['especialidade'])->first()->id,
            'dia' => $dia->format('Y-m-d')
        ]);

        $consulta = $this->agendamentosService->agendar($agendamento);

        return response()->json($consulta, 201);
    }
}
