<?php

namespace App\Exceptions;
class AgendamentoDuplicadoException extends \Exception
{
    public function __construct($message = "Você já possui um agendamento", $code = 0, $prev = null ){
        parent::__construct($message, $code, $prev);
    }
}