<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feriado extends Model
{
    use HasFactory;

    protected $table = 'feriados';

    protected $fillable = [
        'user_id',
        'fecha',
        'motivo',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    /**
     * Psicólogo dueño del feriado.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Turnos fijos afectados por este feriado.
     */
    public function getTurnosFijosAfectadosAttribute()
    {
        $diaSemana = $this->fecha->dayOfWeek;

        return TurnoFijo::where('user_id', $this->user_id)
            ->where('dia_semana', $diaSemana)
            ->where('activo', true)
            ->whereDate('fecha_inicio', '<=', $this->fecha)
            ->where(function ($query) {
                $query->whereNull('fecha_fin')
                    ->orWhereDate('fecha_fin', '>=', $this->fecha);
            })
            ->with([
                'paciente',
                'obraSocial'
            ])
            ->orderBy('hora')
            ->get();
    }
}