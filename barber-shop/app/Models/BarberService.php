<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class BarberService
 *
 * @mixin Builder
 *
 */
class BarberService extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'price'];
    public function appointment() : HasMany {
        return $this->hasMany(Appointment::class);
    }
}
