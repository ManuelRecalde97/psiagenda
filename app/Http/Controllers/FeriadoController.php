<?php

namespace App\Http\Controllers;

use App\Models\Feriado;
use App\Models\TurnoFijo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeriadoController extends Controller
{
    /**
     * Mostrar los feriados del psicólogo
     * junto con los turnos fijos afectados.
     */
    public function index()
    {
        $feriados = Feriado::where('user_id', Auth::id())
            ->orderBy('fecha', 'asc')
            ->get();

        /*
         * Buscamos los turnos fijos que coinciden
         * con cada feriado.
         */
        foreach ($feriados as $feriado) {

            // Carbon devuelve:
            // 0 = domingo
            // 1 = lunes
            // ...
            // 6 = sábado
            $diaSemana = $feriado->fecha->dayOfWeek;

            $turnosFijos = TurnoFijo::where('user_id', Auth::id())
                ->where('activo', true)
                ->where('dia_semana', $diaSemana)

                // El turno fijo debe estar vigente en esa fecha
                ->whereDate('fecha_inicio', '<=', $feriado->fecha)

                ->where(function ($query) use ($feriado) {
                    $query->whereNull('fecha_fin')
                        ->orWhereDate('fecha_fin', '>=', $feriado->fecha);
                })

                ->with([
                    'paciente',
                    'obraSocial',
                ])

                ->orderBy('hora')
                ->get();

            // Guardamos la colección dentro del modelo
            // para que la vista pueda utilizarla.
            $feriado->setRelation(
                'turnosFijosAfectados',
                $turnosFijos
            );
        }

        return view(
            'feriados.index',
            compact('feriados')
        );
    }

    /**
     * Guardar un nuevo feriado.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha' => [
                'required',
                'date',
            ],

            'motivo' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        // Evitamos duplicar la misma fecha.
        $existe = Feriado::where('user_id', Auth::id())
            ->whereDate('fecha', $request->fecha)
            ->exists();

        if ($existe) {
            return back()
                ->withErrors([
                    'fecha' => 'Ya tenés registrado un feriado para esa fecha.',
                ])
                ->withInput();
        }

        Feriado::create([
            'user_id' => Auth::id(),
            'fecha' => $request->fecha,
            'motivo' => $request->motivo,
        ]);

        return back()->with(
            'success',
            'El día sin atención fue registrado correctamente.'
        );
    }

    /**
     * Eliminar un feriado.
     */
    public function destroy($id)
    {
        $feriado = Feriado::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $feriado->delete();

        return back()->with(
            'success',
            'El día sin atención fue eliminado.'
        );
    }
}