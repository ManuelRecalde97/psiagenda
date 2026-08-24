<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turno;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class TurnoController extends Controller
{
    public function index()
    {
        $turnos = Turno::where('user_id', auth()->id())
            ->orderBy('fecha_hora', 'asc')
            ->paginate(15);

        return view('turnos.index', compact('turnos'));
    }

    // Método para generar franjas horarias masivas
    public function store(Request $request)
    {
        $request->validate([
            'dias' => 'required|array',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'duracion' => 'required|integer',
        ]);

        $userId = auth()->id();
        $inicio = Carbon::parse($request->fecha_inicio);
        $fin = Carbon::parse($request->fecha_fin);
        $diasSeleccionados = $request->dias; // Array de días (0=Domingo, 1=Lunes, etc.)
        $duracion = (int) $request->duracion;

        // Recorremos cada día dentro del rango de fechas
        $periodo = CarbonPeriod::create($inicio, $fin);

        foreach ($periodo as $fecha) {
            // Verificamos si el día actual de la semana coincide con los seleccionados
            if (in_array($fecha->dayOfWeek, $diasSeleccionados)) {
                
                $bloques = [];

                // Bloque Mañana
                if ($request->filled('manana_desde') && $request->filled('manana_hasta')) {
                    $bloques[] = [
                        'desde' => $request->manana_desde,
                        'hasta' => $request->manana_hasta,
                    ];
                }

                // Bloque Tarde
                if ($request->filled('tarde_desde') && $request->filled('tarde_hasta')) {
                    $bloques[] = [
                        'desde' => $request->tarde_desde,
                        'hasta' => $request->tarde_hasta,
                    ];
                }

                // Generar los turnos para cada bloque horario
                foreach ($bloques as $bloque) {
                    $horaActual = Carbon::parse($fecha->format('Y-m-d') . ' ' . $bloque['desde']);
                    $horaLimite = Carbon::parse($fecha->format('Y-m-d') . ' ' . $bloque['hasta']);

                    while ($horaActual->copy()->addMinutes($duracion)->lessThanOrEqualTo($horaLimite)) {
                        $turnoInicio = $horaActual->copy();
                        $turnoFin = $horaActual->copy()->addMinutes($duracion);

                        // Evitamos duplicados exactos para este psicólogo
                        $existe = Turno::where('user_id', $userId)
                            ->where('fecha_hora', $turnoInicio)
                            ->exists();

                        if (!$existe) {
                            Turno::create([
                                'user_id' => $userId,
                                'fecha_hora' => $turnoInicio,
                                'estado' => 'disponible',
                            ]);
                        }

                        $horaActual = $turnoFin;
                    }
                }
            }
        }

        return redirect()->route('turnos.index')->with('success', '¡Franjas horarias generadas con éxito!');
    }

    // Demás métodos que ya tengas en tu controlador (como cambiarEstado, destroy, etc.)
}