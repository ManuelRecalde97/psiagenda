<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TurnoController extends Controller
{
    /**
     * Muestra el panel con los turnos pendientes de aprobación y la agenda confirmada.
     */
    public function index()
    {
        $user_id = Auth::id();

        // 1. Bandeja de Entrada: Turnos solicitados desde la web pública que esperan aprobación
        $turnosPendientes = Turno::where('user_id', $user_id)
            ->where('estado', 'pendiente')
            ->where('fecha_hora', '>=', now())
            ->with(['paciente', 'obraSocial'])
            ->orderBy('fecha_hora', 'asc')
            ->get();

        // 2. Agenda Habitual: Turnos que ya fueron aceptados o creados manualmente
        $turnos = Turno::where('user_id', $user_id)
            ->whereIn('estado', ['confirmado', 'ocupado', 'disponible']) 
            ->where('fecha_hora', '>=', now()->startOfDay())
            ->with(['paciente', 'obraSocial'])
            ->orderBy('fecha_hora', 'asc')
            ->get();

        // CORREGIDO: Enviamos $turnos para que la tabla de la vista lo lea bien
        return view('turnos.index', compact('turnosPendientes', 'turnos'));
    }

    /**
     * Acepta la solicitud de un turno nuevo.
     */
    public function aceptar($id)
    {
        $turno = Turno::where('user_id', Auth::id())->findOrFail($id);

        $turno->update([
            'estado' => 'confirmado'
        ]);

        return back()->with('success', '¡El turno ha sido confirmado y agendado con éxito!');
    }

    /**
     * Rechaza la solicitud y libera el horario para que otro paciente pueda reservarlo.
     */
    public function rechazar($id)
    {
        $turno = Turno::where('user_id', Auth::id())->findOrFail($id);

        $turno->update([
            'estado' => 'disponible',
            'patient_id' => null,
            'obra_social_id' => null,
        ]);

        return back()->with('success', 'Solicitud rechazada. El horario vuelve a estar disponible en tu turnero público.');
    }

    /**
     * Cambia el estado del turno (Aceptar o Rechazar desde la tabla).
     */
    public function cambiarEstado(Request $request, $id)
    {
        $turno = Turno::where('user_id', Auth::id())->findOrFail($id);
        $nuevoEstado = $request->input('estado');

        if ($nuevoEstado === 'confirmado') {
            $turno->update(['estado' => 'confirmado']);
            return back()->with('success', '¡El turno ha sido confirmado con éxito!');
        } else {
            // Si rechaza o libera
            $turno->update([
                'estado' => 'disponible',
                'patient_id' => null,
                'obra_social_id' => null,
            ]);
            return back()->with('success', 'Solicitud rechazada. El horario vuelve a estar disponible.');
        }
    }
}