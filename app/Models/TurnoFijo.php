<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurnoFijo extends Model
{
    use HasFactory;

    protected $table = 'turnos_fijos';

    protected $fillable = [
        'user_id',
        'patient_id',
        'obra_social_id',
        'dia_semana',
        'hora',
        'frecuencia',
        'fecha_inicio',
        'fecha_fin',
        'activo',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'activo' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paciente()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function obraSocial()
    {
        return $this->belongsTo(ObraSocial::class);
    }

    public function turnos()
    {
        return $this->hasMany(Turno::class);
    }
}