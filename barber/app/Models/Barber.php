<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Barber
 *
 * @mixin Builder
 */
class Barber extends Model
{
    use HasFactory;
    protected $fillable = ['salary'];

    public function user() : MorphOne {
        return $this->morphOne(User::class, 'userable', 'userable_type', 'userable_id');
    }
    public function appointment() : HasMany {
        return $this->hasMany(Appointment::class);
    }
}
