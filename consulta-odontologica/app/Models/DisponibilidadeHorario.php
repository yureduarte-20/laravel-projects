<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DisponibilidadeHorario extends Model
{
    use HasFactory;

    protected $fillable = ['disponibilidade_id', 'horario_inicio', 'horario_final'];

    public function disponibilidade(): BelongsTo
    {
        return $this->belongsTo(Disponibilidade::class);
    }
    public function consultas(): HasMany
    {
        return $this->hasMany(Consulta::class);
    }
}
