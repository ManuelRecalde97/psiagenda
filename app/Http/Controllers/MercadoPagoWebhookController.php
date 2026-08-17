<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class MercadoPagoWebhookController extends HttpController // o simplemente class MercadoPagoWebhookController
{
    public function handle(Request $request)
    {
        $data = $request->all();

        // Verificamos si la notificación es de un pago aprobado
        if (isset($data['type']) && $data['type'] === 'payment') {
            // Aquí puedes consultar la API de Mercado Pago con el ID del pago ($data['data']['id'])
            // para obtener el email del comprador de forma segura.
            // Para simplificar, asumiremos que recibimos el email o external_reference del usuario:
            
            $email = $data['data']['payer']['email'] ?? null; // O el email que venga en la transacción

            if ($email) {
                $user = User::where('email', $email)->first();

                if ($user) {
                    // Si ya tenía una fecha activa, le sumamos 30 días desde esa fecha, 
                    // si no, se los sumamos desde hoy.
                    $baseDate = ($user->suscripcion_vencimiento && Carbon::parse($user->suscripcion_vencimiento)->isFuture())
                        ? Carbon::parse($user->suscripcion_vencimiento)
                        : Carbon::now();

                    $user->update([
                        'suscripcion_estado' => 'activo',
                        'suscripcion_vencimiento' => $baseDate->addDays(30),
                    ]);
                }
            }
        }

        return response()->json(['status' => 'success'], 200);
    }
}