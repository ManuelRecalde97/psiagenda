<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObraSocial;
use Illuminate\Support\Facades\Auth;

class ObraSocialController extends Controller
{
    public function index()
    {
        $obras = Auth::user()->obrasSociales;
        return view('obras-sociales.index', compact('obras'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'condiciones' => 'nullable|string',
            'copago_adicional' => 'nullable|numeric'
        ]);

        Auth::user()->obrasSociales()->create([
            'nombre' => $request->nombre,
            'condiciones' => $request->condiciones,
            'copago_adicional' => $request->copago_adicional
        ]);

        return back()->with('success', 'Obra social y condiciones agregadas con éxito.');
    }

    public function update(Request $request, $id)
    {
        $obra = ObraSocial::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'nombre' => 'required|string|max:255',
            'condiciones' => 'nullable|string',
            'copago_adicional' => 'nullable|numeric'
        ]);

        $obra->update([
            'nombre' => $request->nombre,
            'condiciones' => $request->condiciones,
            'copago_adicional' => $request->copago_adicional
        ]);

        return back()->with('success', 'Condiciones de la obra social actualizadas.');
    }

    public function destroy($id)
    {
        $obra = ObraSocial::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $obra->delete();

        return back()->with('success', 'Obra social eliminada.');
    }
}