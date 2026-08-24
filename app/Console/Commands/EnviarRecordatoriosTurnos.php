<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Turno;
use Carbon\Carbon;

class EnviarRecordatoriosTurnos extends Command
{
    protected $signature = 'turnos:enviar-recordatorios';
    protected $description = 'Envía recordatorios los domingos (para el lunes) y los lunes (para toda la semana)';

    public function handle()
    {
        $hoy = Carbon::today();
        $diaSemana = $hoy->dayOfWeek; // 0 = Domingo, 1 = Lunes, etc.

        $turnosAEnviar = collect();

        // CASO 1: Si hoy es DOMINGO (0), buscamos los turnos del LUNES
        if ($diaSemana === 0) {
            $lunes = Carbon::tomorrow(); // Lunes
            $turnosAEnviar = Turno::with(['patient', 'user'])
                ->whereDate('fecha_hora', $lunes)
                ->whereIn('estado', ['pendiente', 'confirmado'])
                ->get();

            $this->info("Es domingo. Preparando recordatorios para los turnos del lunes.");
        }

        // CASO 2: Si hoy es LUNES (1), buscamos los turnos de toda la semana (hasta el viernes o domingo)
        elseif ($diaSemana === 1) {
            $inicioSemana = Carbon::today(); // Lunes hoy
            $finSemana = Carbon::today()->endOfWeek(); // Fin de semana (Domingo)

            $turnosAEnviar = Turno::with(['patient', 'user'])
                ->whereBetween('fecha_hora', [$inicioSemana, $finSemana])
                ->whereIn('estado', ['pendiente', 'confirmado'])
                ->get();

            $this->info("Es lunes. Preparando recordatorios para los turnos de toda la semana.");
        }

        // Procesamos el envío para los turnos filtrados
        foreach ($turnosAEnviar as $turno) {
            $psicologo = $turno->user;
            
            if ($psicologo && $psicologo->enviar_recordatorios) {
                $telefonoPaciente = $turno->patient->celular ?? null;
                
                if ($telefonoPaciente) {
                    $fechaTurnoFormateada = Carbon::parse($turno->fecha_hora)->format('d/m/Y H:i');
                    $mensaje = "Hola {$turno->patient->nombre}, te recordamos tu turno con el/la lic. {$psicologo->name} programado para el {$fechaTurnoFormateada} hs.";
                    
                    // Aquí puedes registrar el envío o disparar la API de WhatsApp
                    \Log::info("Recordatorio enviado a {$telefonoPaciente}: {$mensaje}");
                }
            }
        }

        $this->info('¡Proceso de recordatorios semanales finalizado con éxito!');
    }
}