<?php

namespace Database\Seeders;

use App\Models\Servicio;
use Illuminate\Database\Seeder;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        Servicio::create([
            'nombre' => 'Consulta General',
            'descripcion' => 'Examen físico completo y diagnóstico inicial',
            'precio' => 50.00,
            'duracion' => 30
        ]);

        Servicio::create([
            'nombre' => 'Vacunación',
            'descripcion' => 'Aplicación de vacunas según protocolo',
            'precio' => 35.00,
            'duracion' => 20
        ]);

        Servicio::create([
            'nombre' => 'Cirugía',
            'descripcion' => 'Procedimientos quirúrgicos especializados',
            'precio' => 200.00,
            'duracion' => 120
        ]);

        Servicio::create([
            'nombre' => 'Limpieza Dental',
            'descripcion' => 'Limpieza y cuidado dental profesional',
            'precio' => 75.00,
            'duracion' => 60
        ]);

        Servicio::create([
            'nombre' => 'Grooming',
            'descripcion' => 'Baño, corte y cuidado estético',
            'precio' => 60.00,
            'duracion' => 90
        ]);

        Servicio::create([
            'nombre' => 'Análisis Clínico',
            'descripcion' => 'Pruebas de laboratorio y análisis',
            'precio' => 40.00,
            'duracion' => 30
        ]);

        Servicio::create([
            'nombre' => 'Emergencia',
            'descripcion' => 'Atención de urgencia 24/7',
            'precio' => 150.00,
            'duracion' => 45
        ]);

        Servicio::create([
            'nombre' => 'Internación',
            'descripcion' => 'Hospitalización y cuidados intensivos',
            'precio' => 100.00,
            'duracion' => 1440 // 24 horas
        ]);
    }
}
