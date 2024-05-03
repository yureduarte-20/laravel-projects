<?php

namespace App\Exceptions;

class AgendamentoUsuarioInvalidoException extends \Exception
{
    public function __construct($message = 'O agendamento precisa ser para um paciente.')
    {
        parent::__construct($message, 422);
    }
}
