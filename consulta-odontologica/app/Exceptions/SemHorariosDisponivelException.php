<?php

namespace App\Exceptions;

class SemHorariosDisponivelException extends \Exception
{
    public function __construct(string $message = 'O odontologo não possui horario disponível.', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
