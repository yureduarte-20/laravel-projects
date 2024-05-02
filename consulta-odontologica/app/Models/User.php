<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enum\RolesEnum;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function is_admin(): bool
    {
        return $this->hasRole(RolesEnum::ADMIN->name);
    }

    public function is_odontologo(): bool
    {
        return $this->hasRole(RolesEnum::ODONTOLOGO->name);
    }

    public function is_paciente()
    {
        return $this->hasRole(RolesEnum::PACIENTE->name);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin() || $this->is_odontologo();
    }

    public function especialidades(): BelongsToMany
    {
        return $this->belongsToMany(Especialidade::class)
            ->using(EspecialidadeUser::class)
            ->withTimestamps();
    }

    public function agenda()
    {
        return $this->hasOne(Agenda::class);
    }
    public function consultas(): HasMany
    {
        return $this->hasMany(Consulta::class);
    }
}
