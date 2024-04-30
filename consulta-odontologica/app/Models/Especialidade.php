<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin Builder
 */
class Especialidade extends Model
{
    use HasFactory;
    protected $fillable = [
        'nome',
        'descricao',
        'tempo_medio_consulta_minutos'
    ];
    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->using(EspecialidadeUser::class)
            ->withTimestamps();
    }
}
