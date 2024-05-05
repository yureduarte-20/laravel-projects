<?php

namespace App\Models;

use App\Enum\StatusConsulta;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin Builder
 */
class Consulta extends Model
{
    use HasFactory;

    protected $fillable = ['dia', 'agenda_id', 'horario_id', 'user_id', 'status', 'especialidade_id'];

    public function especialidade(): BelongsTo
    {
        return $this->belongsTo(Especialidade::class);
    }

    public function agenda(): BelongsTo
    {
        return $this->belongsTo(Agenda::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function horario(): BelongsTo
    {
        return $this->belongsTo(Horario::class);
    }

    protected $casts = [
        'dia' => 'date',
        'status' => StatusConsulta::class,
    ];
}
