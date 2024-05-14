<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin Builder
 */
class Car extends Model
{
    use HasFactory;
    protected $fillable = [
      'model',
      'year',
      'brand',
      'is_available',
      'plate'
    ];
    public function rentals() : HasMany {
        return $this->hasMany(Rental::class);
    }
    public function scopeAvailable(Builder $query) : void{
        $query->where('is_available', '=', true);
    }
    public function price() : HasOne {
        return $this->hasOne(Price::class);
    }
}
