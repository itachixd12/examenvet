<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionClinica extends Model
{
    protected $table = 'configuracion_clinica';
    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'email',
        'latitud',
        'longitud',
        'horario_apertura',
        'horario_cierre',
        'telefono_emergencia',
        'firebase_config',
        'mapbox_token'
    ];

    protected $casts = [
        'firebase_config' => 'json'
    ];
}
