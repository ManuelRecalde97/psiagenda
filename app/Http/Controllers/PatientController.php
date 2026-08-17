<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PatientController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        // Esto busca solo los pacientes del psicólogo que está logueado
        $patients = Patient::where('user_id', Auth::id())->get();
        
        return view('patients.index', compact('patients'));
    }

// Guarda el paciente en la base de datos
   
    public function create()
    {
        return view('patients.create');
    }

   public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'celular' => 'nullable|string|max:50',
            'obra_social' => 'nullable|string|max:255',
            'motivo_consulta' => 'nullable|string',
        ]);
              
        // Guardamos el paciente vinculado al usuario actual
        $request->user()->patients()->create($validated);

        return redirect()->route('patients.index')->with('success', 'Paciente registrado correctamente.');
    }
// Muestra el formulario con los datos cargados
    public function edit(Patient $patient)
    {
        // Esto asegura que solo el dueño del paciente pueda editarlo
        $this->authorize('update', $patient);
        return view('patients.edit', compact('patient'));
    }

    // Actualiza los cambios en la base de datos
    public function update(Request $request, Patient $patient)
    {
        $this->authorize('update', $patient);
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'celular' => 'nullable|string|max:20',
            'obra_social' => 'nullable|string|max:255',
        ]);

        $patient->update($validated);

        return redirect()->route('patients.index')->with('success', 'Paciente actualizado.');
    }

    public function show(Patient $patient)
   {
    // Carga las notas del paciente
    $notes = $patient->notes()->latest()->get();
    return view('patients.show', compact('patient', 'notes'));
   }

}
