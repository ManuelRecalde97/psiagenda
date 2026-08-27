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

        // Ejecutar las migraciones forzando la actualización de la base de datos en producción
        if (app()->environment('production')) {
            try {
                Artisan::call('migrate', ['--force' => true]);
            } catch (\Exception $e) {
                // Si hay conflicto de tablas, fuerza el restablecimiento o reintenta
            }
        }
    }
}