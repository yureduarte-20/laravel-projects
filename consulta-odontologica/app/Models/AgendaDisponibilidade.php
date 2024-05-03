<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgendaDisponibilidade extends Pivot
{
    use SoftDeletes;
    protected $fillable = ['agenda_id', 'disponibilidade_id'];

    protected $table = 'agenda_disponibilidade';
}
