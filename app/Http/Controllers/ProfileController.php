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

        // 1. Llena los datos estándar validados por el FormRequest (incluye nombre, email, etc.)
        $user->fill($request->validated());

        // 2. Si el correo electrónico cambió, reseteamos la verificación de email
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // 3. Asignación y guardado de las preferencias del turnero público (SaaS multi-usuario)
        $user->mensaje_bienvenida = $request->input('mensaje_bienvenida');
        $user->activar_edad = $request->has('activar_edad');
        $user->activar_modalidad = $request->has('activar_modalidad');
        $user->activar_motivo = $request->has('activar_motivo');

        // 4. Guardamos todos los cambios en la base de datos de una sola vez
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