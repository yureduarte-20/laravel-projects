<?php

namespace App\DTOs;

use WendellAdriel\ValidatedDTO\ValidatedDTO;

class AgendamentoDTO extends ValidatedDTO
{
    public string $horario;

    public $especialidade_id;

    public $agenda_id;
    public $disponibilidade_horario_id;

    public $user_id;

    protected function rules(): array
    {
        return [
            'user_id' => ['exists:users,id', 'required'],
            'agenda_id' => ['exists:agendas,id', 'required'],
            'horario' => ['required', 'date'],
            'especialidade_id' => ['required', 'exists:especialidades,id'],
            'disponibilidade_horario_id' => ['required', 'exists:disponibilidade_horarios,id']
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
