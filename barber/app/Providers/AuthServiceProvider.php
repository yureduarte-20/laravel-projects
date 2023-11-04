<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Barber;
use App\Models\BarberService;
use App\Policies\BarberPolicy;
use App\Policies\BarberServicePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        BarberService::class => BarberServicePolicy::class,
        Barber::class => BarberPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
