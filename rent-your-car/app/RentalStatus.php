<?php

namespace App;

enum RentalStatus: string
{
    case RESERVED = 'RESERVED';
    case IN_PROGRESS = 'IN_PROGRESS';
    case CANCELED = 'CANCELED';
    case LATE = 'LATE';
    case FINISHED = 'FINISHED';
}
