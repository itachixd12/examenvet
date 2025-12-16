<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialMedico extends Model
{
    protected $table = 'historial_medico';
    protected $fillable = [
        'mascota_id',
        'veterinario_id',
        'cita_id',
        'tipo',
        'fecha',
        'descripcion',
        'resultado',
        'medicamento',
        'dosis',
        'notas'
    ];

    protected $dates = ['fecha'];

    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'mascota_id');
    }

    public function veterinario()
    {
        return $this->belongsTo(Veterinario::class, 'veterinario_id');
    }

    public function cita()
    {
        return $this->belongsTo(Cita::class, 'cita_id');
    }
}
