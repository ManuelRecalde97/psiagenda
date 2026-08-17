<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    use HasFactory;

    protected $table = 'turnos';

    protected $fillable = [
        'user_id',
        'fecha_hora',
        'estado',
        'patient_id',
        'obra_social_id',
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
        return $this->belongsTo(ObraSocial::class, 'obra_social_id');
    }
}