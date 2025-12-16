<?php

namespace Database\Seeders;

use App\Models\Veterinario;
use Illuminate\Database\Seeder;

class VeterinarioSeeder extends Seeder
{
    public function run(): void
    {
        Veterinario::create([
            'nombre' => 'Dr. Juan García',
            'email' => 'juan.garcia@petcare.com',
            'telefono' => '+593 99 000 0001',
            'especialidad' => 'Medicina General',
            'foto' => null
        ]);

        Veterinario::create([
            'nombre' => 'Dra. María López',
            'email' => 'maria.lopez@petcare.com',
            'telefono' => '+593 99 000 0002',
            'especialidad' => 'Cirugía Veterinaria',
            'foto' => null
        ]);

        Veterinario::create([
            'nombre' => 'Dr. Carlos Rodríguez',
            'email' => 'carlos.rodriguez@petcare.com',
            'telefono' => '+593 99 000 0003',
            'especialidad' => 'Medicina Interna',
            'foto' => null
        ]);

        Veterinario::create([
            'nombre' => 'Dra. Ana Martínez',
            'email' => 'ana.martinez@petcare.com',
            'telefono' => '+593 99 000 0004',
            'especialidad' => 'Odontología Veterinaria',
            'foto' => null
        ]);
    }
}
