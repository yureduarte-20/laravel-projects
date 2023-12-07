<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
/**
 * @mixin Builder
 */
class Price extends Model
{
    use HasFactory;
    protected $fillable = [
        'price_per_day',
        'late_price_per_day'
    ];
    public function car() : BelongsTo {
       return $this->belongsTo(Car::class);
    }
}
