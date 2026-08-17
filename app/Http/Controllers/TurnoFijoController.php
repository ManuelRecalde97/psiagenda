<?php

namespace App\Http\Controllers;

use App\Models\TurnoFijo;
use App\Models\Patient;
use App\Models\ObraSocial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TurnoFijoController extends Controller
{
    /**
     * Mostrar los turnos fijos del psicólogo.
     */
    public function index()
    {
        $turnosFijos = Auth::user()
            ->turnosFijos()
            ->with(['paciente', 'obraSocial'])
            ->orderBy('dia_semana')
            ->orderBy('hora')
            ->get();

        $pacientes = Patient::where('user_id', Auth::id())
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->get();

        $obrasSociales = ObraSocial::where('user_id', Auth::id())
            ->orderBy('nombre')
            ->get();

        return view('turnos-fijos.index', compact(
            'turnosFijos',
            'pacientes',
            'obrasSociales'
        ));
    }

    /**
     * Crear un nuevo turno fijo.
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => [
                'required',
                'exists:patients,id',
            ],

            'obra_social_id' => [
                'nullable',
                'exists:obras_sociales,id',
            ],

            'dia_semana' => [
                'required',
                'integer',
                'between:0,6',
            ],

            'hora' => [
                'required',
                'date_format:H:i',
            ],

            'frecuencia' => [
                'required',
                'in:semanal',
            ],

            'fecha_inicio' => [
                'required',
                'date',
            ],

            'fecha_fin' => [
                'nullable',
                'date',
                'after_or_equal:fecha_inicio',
            ],
        ]);

        // Verificamos que el paciente pertenezca al psicólogo
        $paciente = Patient::where('id', $request->patient_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Si seleccionó obra social, verificamos que pertenezca al psicólogo
        if ($request->filled('obra_social_id')) {
            ObraSocial::where('id', $request->obra_social_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
        }

        TurnoFijo::create([
            'user_id' => Auth::id(),
            'patient_id' => $paciente->id,
            'obra_social_id' => $request->obra_social_id,
            'dia_semana' => $request->dia_semana,
            'hora' => $request->hora,
            'frecuencia' => $request->frecuencia,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'activo' => true,
        ]);

        return back()->with(
            'success',
            '¡Turno fijo creado correctamente!'
        );
    }

    /**
     * Activar o desactivar un turno fijo.
     */
    public function cambiarEstado($id)
    {
        $turnoFijo = TurnoFijo::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $turnoFijo->update([
            'activo' => !$turnoFijo->activo,
        ]);

        return back()->with(
            'success',
            'El estado del turno fijo fue actualizado.'
        );
    }

    /**
     * Eliminar un turno fijo.
     */
    public function destroy($id)
    {
        $turnoFijo = TurnoFijo::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $turnoFijo->delete();

        return back()->with(
            'success',
            'Turno fijo eliminado.'
        );
    }
}