<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Muestra el formulario de perfil del usuario.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Actualiza la información del perfil del usuario,
     * incluyendo los ajustes personalizados para su turnero público.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

    // Guardado directo usando los datos del request sin pasar por validated() para probar
    $user->name = $request->input('name');
    $user->email = $request->input('email');

    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    $user->mensaje_bienvenida = $request->input('mensaje_bienvenida');
    $user->activar_edad = $request->has('activar_edad') ? 1 : 0;
    $user->activar_modalidad = $request->has('activar_modalidad') ? 1 : 0;
    $user->activar_motivo = $request->has('activar_motivo') ? 1 : 0;
    $user->activar_aviso_menores = $request->has('activar_aviso_menores') ? 1 : 0;
    $user->mensaje_aviso_menores = $request->input('mensaje_aviso_menores');
    
    $user->enviar_recordatorios = $request->input('enviar_recordatorios');
    $user->frecuencia_recordatorio = $request->input('frecuencia_recordatorio');

    $user->save();

    return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Elimina la cuenta del usuario de forma segura.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
