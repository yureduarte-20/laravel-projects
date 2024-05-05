<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class EspecialidadeUser extends Pivot
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'especialidade_id'];

    protected $table = 'especialidade_user';
}
