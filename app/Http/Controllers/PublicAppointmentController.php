<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;

class PublicAppointmentController extends Controller
{
    public function create()
    {
        // Muestra la vista pública del formulario de turnos
        return view('public.appointment');
    }

    public function store(Request $request)
    {
        // Validamos los datos básicos sin pedir contraseña
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'celular' => 'required|string|max:50',
            'fecha_hora' => 'required|date',
        ]);

        // Verificamos si el paciente ya existe por su celular para no duplicarlo, o lo creamos
        $patient = Patient::firstOrCreate(
            ['celular' => $validated['celular']],
            [
                'nombre' => $validated['nombre'],
                'apellido' => $validated['apellido'],
            ]
        );

        // Aquí puedes guardar el turno si ya tienes creada una tabla/modelo de turnos, 
        // por ahora con esto el paciente queda registrado automáticamente en el sistema del psicólogo.

        return back()->with('success', '¡Tu turno ha sido reservado con éxito!');
    }
}