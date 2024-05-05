<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgendaHorario extends Pivot
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['agenda_id', 'horario_id'];

    protected $table = 'agenda_horario';
}
