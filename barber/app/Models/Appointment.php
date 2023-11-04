<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Appointment
 *
 * @mixin Builder
 */
class Appointment extends Model
{
    use HasFactory;
    protected $fillable = ['time', 'is_cancelled'];

    public function baber() : BelongsTo {
        return $this->belongsTo(Barber::class);
    }
    public function customer() : BelongsTo {
        return $this->belongsTo(Customer::class);
    }
    public function baberService() : BelongsTo {
        return $this->belongsTo(BarberService::class);
    }
}
