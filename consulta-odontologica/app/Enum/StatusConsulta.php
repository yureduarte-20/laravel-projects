<?php

namespace App\Enum;

enum StatusConsulta
{
    case AGENDADO;
    case CANCELADA;
    case ADIADA;
    case ENCERRADA;
    case EM_ANDAMENTO;
}
