<?php

namespace App\Models;

use App\Enum\Semanas;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Horario extends Model
{
    use HasFactory;

    public function agendas(): BelongsToMany
    {
        return $this->belongsToMany(Agenda::class)
            ->using(AgendaHorario::class)
            ->withTimestamps();
    }

    protected $fillable = ['agenda_id', 'horario_id'];

    protected $casts = [
        'dia_semana' => Semanas::class,
    ];
}
