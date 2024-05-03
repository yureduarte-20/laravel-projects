<?php

namespace App\Models;

use App\Enum\Semanas;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Disponibilidade extends Model
{
    use HasFactory;
    protected $fillable = ['dia_semana', 'horario'];
    public function agendas() : BelongsToMany
    {
        return $this->belongsToMany(Agenda::class)
        ->using(AgendaDisponibilidade::class)->withTimestamps();
    }
    protected $casts = [  
        'dia_semana' => Semanas::class
     ];
}
