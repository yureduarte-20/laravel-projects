<?php

namespace App\DTOs;

use WendellAdriel\ValidatedDTO\ValidatedDTO;

class AgendamentoDTO extends ValidatedDTO
{
    public string $dia;

    public $especialidade_id;

    public $horario_id;

    public $agenda_id;

    public $user_id;

    protected function rules(): array
    {
        return [
            'user_id' => ['exists:users,id', 'required'],
            'agenda_id' => ['exists:agendas,id', 'required'],
            'dia' => ['required', 'date'],
            'especialidade_id' => ['required', 'exists:especialidades,id'],
            'horario_id' => ['required', 'exists:horarios,id'],
        ];
    }

    protected function defaults(): array
    {
        return [

        ];
    }

    protected function casts(): array
    {
        return [

        ];
    }
}
