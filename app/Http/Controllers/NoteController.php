<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Note;

class NoteController extends Controller
{
    public function store(Request $request, Patient $patient)
    {
        // Validamos la información del formulario híbrido
        $validated = $request->validate([
            'fecha' => 'required|date',
            'diagnostico' => 'nullable|string|max:255',
            'evolucion' => 'required|string',
            'tareas_indicaciones' => 'nullable|string',
            'monto' => 'nullable|numeric',
            'estado_pago' => 'required|string',
            'tipo_pago' => 'required|string',
        ]);

        // Guardamos la nota asociada al paciente actual
        $patient->notes()->create($validated);

        return back()->with('success', 'Evolución guardada con éxito.');
    }
}