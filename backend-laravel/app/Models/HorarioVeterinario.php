<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioVeterinario extends Model
{
    protected $table = 'horarios_veterinarios';
    protected $fillable = [
        'veterinario_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin'
    ];

    public function veterinario()
    {
        return $this->belongsTo(Veterinario::class, 'veterinario_id');
    }
}
