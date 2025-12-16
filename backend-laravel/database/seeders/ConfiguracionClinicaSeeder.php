<?php

namespace Database\Seeders;

use App\Models\ConfiguracionClinica;
use Illuminate\Database\Seeder;

class ConfiguracionClinicaSeeder extends Seeder
{
    public function run(): void
    {
        ConfiguracionClinica::create([
            'nombre' => 'PetCare - Clínica Veterinaria',
            'direccion' => 'Calle Principal 123, Sangolqui, Pichincha',
            'telefono' => '+593 2 XXXX XXXX',
            'email' => 'info@petcare.com',
            'latitud' => -0.3523, // Coordenadas de ejemplo (Sangolqui)
            'longitud' => -78.4834,
            'horario_apertura' => '08:00',
            'horario_cierre' => '19:00',
            'telefono_emergencia' => '+593 99 999 9999',
            'mapbox_token' => 'pk.eyJ1IjoiZXJpY2tzdGV2ZW4xNyIsImEiOiJjbWl6M25jcjgwbTJ4M2tweTJ5dXEzc29iIn0.gCKA1vCnL0A1rY2qSL3uqQ',
            'firebase_config' => null // Se configura después
        ]);
    }
}
