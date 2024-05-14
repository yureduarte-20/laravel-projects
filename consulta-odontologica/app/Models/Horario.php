<?php

namespace App\Models;

use App\Enum\Semanas;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    public function labelOption(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->dia_semana. ' as '. $this->horario
        );
    }
    protected $fillable = ['dia_semana', 'horario'];

    /* protected $casts = [
        'dia_semana' => Semanas::class,
    ]; */
}
