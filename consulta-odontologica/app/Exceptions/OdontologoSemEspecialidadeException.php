<?php
namespace App\Exceptions;
class OdontologoSemEspecialidadeException extends \Exception {
    public function __construct($message = "Odontologo não possui especialidade requerida.", $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
