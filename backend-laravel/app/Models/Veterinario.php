<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Veterinario extends Model
{
    protected $table = 'veterinarios';
    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'especialidad',
        'foto'
    ];

    public function citas()
    {
        return $this->hasMany(Cita::class, 'veterinario_id');
    }

    public function historial()
    {
        return $this->hasMany(HistorialMedico::class, 'veterinario_id');
    }

    public function horarios()
    {
        return $this->hasMany(HorarioVeterinario::class, 'veterinario_id');
    }
}
