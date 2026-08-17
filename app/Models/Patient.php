<?php

namespace App\Models;

use App\Models\TurnoFijo;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    // Agrega estas líneas:
    protected $fillable = [
        'user_id',
        'nombre',
        'apellido',
        'celular',
        'obra_social',
        'motivo_consulta',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function notes()
    {
        return $this->hasMany(Note::class);
    }
    public function turnosFijos()
    {
    return $this->hasMany(TurnoFijo::class);
    }

}