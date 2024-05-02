<?php

namespace App\DTOs;

use WendellAdriel\ValidatedDTO\ValidatedDTO;

class AgendamentoDTO extends ValidatedDTO
{
    public string $horario;

    public $especialidade_id;

    public $agenda_id;

    public $user_id;

    protected function rules(): array
    {
        return [
            'user_id' => ['exists:users,id'],
            'agenda_id' => ['exists:agendas,id'],
            'horario' => ['required', 'date'],
            'especialidade_id' => ['required', 'exists:especialidades,id'],
        ];
    }

    protected function defaults(): array
    {
        return [
            'user_id' => auth()->user()->id,
        ];
    }

    protected function casts(): array
    {
        return [

        ];
    }
}
