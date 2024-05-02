<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * @mixin Builder
 */
class Agenda extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function disponibilidades(): HasMany
    {
        return $this->hasMany(Disponibilidade::class);
    }

    public function horarios(): HasManyThrough
    {
        return $this->hasManyThrough(DisponibilidadeHorario::class, Disponibilidade::class);
    }
}
