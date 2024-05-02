<?php

namespace App\Models;

use App\Enum\Semanas;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Disponibilidade extends Model
{
    use HasFactory;

    protected $fillable = ['agenda_id', 'disponibilidade_semana'];

    public function horarios(): HasMany
    {
        return $this->hasMany(DisponibilidadeHorario::class);
    }

    public function agenda(): BelongsTo
    {
        return $this->belongsTo(Agenda::class);
    }
    protected $casts =[
        'disponibilidade_semana' => Semanas::class
    ];
}
