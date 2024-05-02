<?php

namespace App\Models;

use App\Enum\StatusConsulta;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin Builder
 */
class Consulta extends Model
{
    use HasFactory;

    protected $fillable = ['especialidade_id', 'agenda_id', 'user_id', 'horario' , 'status', 'disponibilidade_horario_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function especialidade(int $id): BelongsTo
    {
        return $this->belongsTo(especialidade::class, $id);
    }

    public function agenda(): BelongsTo
    {
        return $this->belongsTo(Agenda::class);
    }
    public function horario() : BelongsTo
    {
        return $this->belongsTo(DisponibilidadeHorario::class);
    }


    protected $casts = [
        'horario' => 'datetime',
        'status' => StatusConsulta::class,
    ];
}
