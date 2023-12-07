<?php

namespace App\Models;

use App\RentalStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
/**
 * @mixin Builder
 */
class Rental extends Model
{
    use HasFactory;
    protected $fillable = [
        'status',
        'expected_return',
        'expected_rental',
        'rental_date',
        'returned_at',
        'total_cost',
        'estimated_cost'
    ];
    protected $casts = [
        'status' => RentalStatus::class,
        'rental_date' => 'datetime',
        'expected_return' => 'datetime',
        'returned_at' => 'datetime',

    ];

    public function car(): BelongsTo{
        return $this->belongsTo(Car::class);
    }
    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }
    public function scopeLateRentals(Builder $builder):void{
        $builder->whereDate('expected_return', '<',  Carbon::now())
                ->whereNull('returned_at');
    }
    public function scopeStatus(Builder $builder, RentalStatus $status):void{
        $builder->where('status', '=', $status->value);
    }
}
