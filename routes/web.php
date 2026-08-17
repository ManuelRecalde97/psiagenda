<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PublicAppointmentController;
use App\Http\Controllers\ObraSocialController;
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\PublicTurnoController;
use App\Http\Controllers\MercadoPagoWebhookController; 
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TurnoFijoController;
use App\Http\Controllers\FeriadoController;


// Webhook de Mercado Pago
Route::post('/webhook/mercadopago', [MercadoPagoWebhookController::class, 'handle']);

// Rutas Públicas (para los pacientes)
Route::get('/turno/{username}', [PublicTurnoController::class, 'show'])->name('public.turno');
Route::post('/turno/{username}', [PublicTurnoController::class, 'store'])->name('public.turno.store');

Route::get('/reservar', [PublicAppointmentController::class, 'create'])->name('public.appointment');
Route::post('/reservar', [PublicAppointmentController::class, 'store'])->name('public.appointment.store');

Route::get('/', function () {
    return view('welcome');
});

// Ruta de pago de suscripción
Route::get('/suscripcion-pagar', function () {
    return view('suscripcion.pagar');
})->name('suscripcion.pagar')->middleware('auth');

// Rutas Protegidas (Requieren estar logueado Y tener la suscripción al día)
Route::middleware(['auth', 'check.subscription'])->group(function () {
    
    // Dashboard principal con el controlador que creamos
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pacientes y Notas
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/patients/create', [PatientController::class, 'create'])->name('patients.create');
    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
    Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');
    Route::get('/patients/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');
    Route::post('/patients/{patient}/notes', [NoteController::class, 'store'])->name('notes.store');

    // Turnos del Psicólogo
    Route::get('/mis-turnos', [TurnoController::class, 'index'])->name('turnos.index');
    Route::post('/mis-turnos', [TurnoController::class, 'store'])->name('turnos.store');
    Route::post('/mis-turnos/{id}/estado', [TurnoController::class, 'cambiarEstado'])->name('turnos.estado');
    Route::delete('/mis-turnos/{id}', [TurnoController::class, 'destroy'])->name('turnos.destroy');
    Route::get('/api/turnos', [TurnoController::class, 'getTurnosJson'])->name('turnos.json');
    Route::patch('/turnos/{id}/aceptar', [App\Http\Controllers\TurnoController::class, 'aceptar'])->name('turnos.aceptar');
    Route::patch('/turnos/{id}/rechazar', [App\Http\Controllers\TurnoController::class, 'rechazar'])->name('turnos.rechazar');
   
     // Turnos Fijos
     Route::get('/mis-turnos-fijos', [TurnoFijoController::class, 'index'])
     ->name('turnos-fijos.index');
     Route::post('/mis-turnos-fijos', [TurnoFijoController::class, 'store'])
     ->name('turnos-fijos.store');
     Route::post('/mis-turnos-fijos/{id}/estado', [TurnoFijoController::class, 'cambiarEstado'])
     ->name('turnos-fijos.estado');
     Route::delete('/mis-turnos-fijos/{id}', [TurnoFijoController::class, 'destroy'])
     ->name('turnos-fijos.destroy');
     
      // Feriados / días sin atención
        Route::get('/feriados', [FeriadoController::class, 'index'])
        ->name('feriados.index');
        Route::post('/feriados', [FeriadoController::class, 'store'])
        ->name('feriados.store');
        Route::delete('/feriados/{id}', [FeriadoController::class, 'destroy'])
        ->name('feriados.destroy');
       Route::post('/feriados/{feriado}/reprogramar', [FeriadoController::class, 'reprogramar'])
        ->name('feriados.reprogramar');

    // Obras Sociales del Psicólogo
    Route::get('/mis-obras-sociales', [ObraSocialController::class, 'index'])->name('obras.index');
    Route::post('/mis-obras-sociales', [ObraSocialController::class, 'store'])->name('obras.store');
    Route::delete('/mis-obras-sociales/{id}', [ObraSocialController::class, 'destroy'])->name('obras.destroy');
});

require __DIR__.'/auth.php';