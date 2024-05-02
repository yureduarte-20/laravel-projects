<?php

namespace App\Http\Controllers\Api;

use App\DTOs\AgendamentoDTO;
use App\Http\Controllers\Controller;
use App\Models\Especialidade;
use App\Service\AgendamentosService;
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
            'agenda_id' => 'required|exists:users,id',
            'horario' => 'required|date',
            'especialidade' => 'required|exists:especialidades,nome',
        ]);

        $agendamento = new AgendamentoDTO([
            'user_id' => $request->user()->id,
            'horario' => $validated['horario'],
            'agenda_id' => $validated['agenda_id'],
            'especialidade_id' => Especialidade::where('nome', $validated['especialidade'])->first()->id,
        ]);

        $consulta = $this->agendamentosService->agendar($agendamento);

        return response()->json($consulta, 201);
    }
}
