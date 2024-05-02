<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Especialidade;
use App\Service\AgendamentosService;

class EspecialidadeController extends Controller
{
    public function __construct(
        private AgendamentosService $agendamentos
    ) {
        $this->authorizeResource(Especialidade::class, 'especialidade');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Especialidade::paginate(20)->withQueryString();
    }

    /**
     * Display the specified resource.
     */
    public function show(Especialidade $especialidade)
    {
        return Especialidade::find($especialidade);
    }
}
