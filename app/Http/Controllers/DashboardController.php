<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Turno;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
   public function index()
{
    $userId = Auth::id();

    $totalPacientes = Patient::where('user_id', $userId)->count();
    
    $totalTurnos = Turno::where('user_id', $userId)
        ->whereBetween('fecha_hora', [now()->startOfWeek(), now()->endOfWeek()])
        ->count();

    // Traemos los turnos de hoy ordenados por horario junto con su paciente
    $turnohoy = Turno::where('user_id', $userId)
        ->whereDate('fecha_hora', today())
        ->with('paciente') // Relación con el modelo Patient
        ->orderBy('fecha_hora', 'asc')
        ->get();

    $facturacion = 0; 

    return view('dashboard', compact('totalPacientes', 'totalTurnos', 'turnohoy', 'facturacion'));
}
}