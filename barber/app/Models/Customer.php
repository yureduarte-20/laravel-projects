<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Customer
 *
 * @mixin Builder
 */

class Customer extends Model
{
    use HasFactory;
    protected $fillable = ['phone'];
    public function user() : MorphOne {
        return $this->morphOne(User::class, 'userable', 'userable_type', 'userable_id');
    }
    public function appointment() : HasMany {
        return $this->hasMany(Appointment::class);
    }
    protected static function booted () {
        static::deleting(function(Customer $user) { // before delete() method call this
            $user->user()->delete();
        });
    }
}
