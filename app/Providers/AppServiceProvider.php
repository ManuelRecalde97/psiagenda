<?php

namespace App\Providers;

use App\Models\Patient;
use App\Policies\PatientPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\URL;

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
        // Forzar HTTPS en producción para evitar avisos de formulario no seguro
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Ejecutar todas las migraciones automáticamente en producción si la base de datos está vacía o faltan tablas
        if (app()->environment('production')) {
            Artisan::call('migrate', ['--force' => true]);
        }
    }
}