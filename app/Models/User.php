<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\TurnoFijo;
use App\Models\Feriado;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nombre',
        'apellido',
        'slug',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function turnos()
    {
        return $this->hasMany(Turno::class);
    }
    
    public function feriados()
    {
        return $this->hasMany(Feriado::class);
    }
    
    public function obrasSociales()
    {
        return $this->hasMany(ObraSocial::class);
    }

    public function turnosFijos()
    {
        return $this->hasMany(TurnoFijo::class);
    }
    
    protected static function booted()
    {
        static::saving(function ($user) {
            // Si usa nombre y apellido separados
            if (!empty($user->nombre) && !empty($user->apellido)) {
                $user->slug = Str::slug($user->nombre . '-' . $user->apellido);
            } 
            // Si usa el campo unificado 'name' de Laravel
            elseif (!empty($user->name)) {
                $user->slug = Str::slug($user->name);
            }
        });
    }
}