<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $table = 'citas';
    protected $fillable = [
        'user_id',
        'mascota_id',
        'veterinario_id',
        'servicio_id',
        'servicio',
        'veterinario',
        'fecha',
        'hora',
        'motivo',
        'notas',
        'estado',
        'status',
        'especie'
    ];

    protected $dates = ['fecha'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'mascota_id');
    }

    public function veterinario()
    {
        return $this->belongsTo(Veterinario::class, 'veterinario_id');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    // Accessor y Mutator para sincronizar status y estado
    public function getStatusAttribute($value)
    {
        // Mapear valores de estado a status
        $mapping = [
            'pendiente' => 'Próxima',
            'confirmada' => 'Próxima',
            'completada' => 'Completada',
            'cancelada' => 'Cancelada'
        ];
        
        if ($value) {
            return $value;
        }
        
        return $mapping[$this->estado] ?? 'Próxima';
    }

    public function setStatusAttribute($value)
    {
        // Mapear status a valores del ENUM estado
        $mapping = [
            'Próxima' => 'confirmada',
            'Completada' => 'completada',
            'Cancelada' => 'cancelada',
            'Agendada' => 'confirmada'
        ];
        
        $estadoValue = $mapping[$value] ?? 'pendiente';
        $this->attributes['status'] = $value;
        $this->attributes['estado'] = $estadoValue;
    }
}
