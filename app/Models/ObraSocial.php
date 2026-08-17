<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObraSocial extends Model
{
    use HasFactory;

    protected $table = 'obras_sociales';
    protected $fillable = [
        'user_id', 
        'nombre',
        'condiciones',
        'copago_adicional',];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function turnos()
    {
    return $this->hasMany(Turno::class, 'obra_social_id');
    }
}