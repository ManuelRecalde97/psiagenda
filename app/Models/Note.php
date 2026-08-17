<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'fecha',
        'diagnostico',
        'evolucion',
        'tareas_indicaciones',
        'monto',
        'estado_pago',
        'tipo_pago',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}