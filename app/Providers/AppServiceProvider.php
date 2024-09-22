<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
//adicionados
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL; // Importar URL para forzar HTTPS

use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Forzar HTTPS solo en producción
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Compartir la información de autenticación con todas las vistas de Inertia
        Inertia::share([
            'auth' => function () {
                return [
                    'user' => Auth::user(),
                ];
            },
        ]);
    }
}
