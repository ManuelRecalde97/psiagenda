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
        // Forzar HTTPS en producción para evitar el aviso de formulario no seguro
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Si la tabla sessions no existe, ejecuta las migraciones automáticamente
        if (app()->environment('production') && !Schema::hasTable('sessions')) {
            Artisan::call('migrate', ['--force' => true]);
        }
    }
}