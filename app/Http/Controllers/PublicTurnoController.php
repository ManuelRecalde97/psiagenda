<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Turno;
use App\Models\Patient;
use App\Models\ObraSocial;

class PublicTurnoController extends Controller
{
    // Muestra la vista pública con los turnos y obras sociales del psicólogo
    public function show($id)
    {
        $psicologo = User::with([
            'turnos' => function ($q) {
                $q->where('estado', 'disponible')
                ->where('fecha_hora', '>=', now()) //filtramos para ocultar dias pasados 
                ->orderBy('fecha_hora', 'asc');
            },
            'obrasSociales'
        ])->findOrFail($id);

        return view('public.turno', compact('psicologo'));
    }

    // Procesa la reserva del paciente
    public function store(Request $request, $id)
    {
        $request->validate([
            'turno_id' => 'required|exists:turnos,id',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'celular' => 'required|string|max:50',
            'obra_social_id' => 'required',
            'edad_paciente' => 'nullable|string|max:50',
            'modalidad' => 'nullable|string|in:presencial,virtual',
            'motivo_consulta' => 'nullable|string|max:255',
        ]);

        // Verificamos que el turno pertenezca al psicólogo
        // y que todavía esté disponible.
        $turno = Turno::where('id', $request->turno_id)
            ->where('user_id', $id)
            ->where('estado', 'disponible')
            ->firstOrFail();

        // Buscamos la obra social solamente si
        // el paciente no eligió Particular.
        $obraSocial = null;

        if ($request->obra_social_id !== 'particular') {
            $obraSocial = ObraSocial::where('id', $request->obra_social_id)
                ->where('user_id', $id)
                ->firstOrFail();
        }

        // Buscamos o creamos el paciente.
        $patient = Patient::firstOrCreate(
            [
                'celular' => $request->celular,
                'user_id' => $id
            ],
            [
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
            ]
        );

        // Actualizamos los datos por si el paciente ya existía.
        $patient->update([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'edad'=> $request->edad_paciente,
            'modalidad_preferida'=> $request->modalidad,
        ]);

        // Asignamos el paciente y la obra social al turno.
        $turno->update([
            'patient_id' => $patient->id,
            'obra_social_id' => $obraSocial ? $obraSocial->id : null,
            'estado' => 'pendiente',
        ]);

        return back()->with(
            'success',
            '¡Solicitud de turno enviada con éxito! El profesional te confirmará a la brevedad.'
        );
    }
}