<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Cita;

echo "\n=== Verificación de Citas ===\n";
$total = Cita::count();
echo "Total de citas: " . $total . "\n";

if ($total > 0) {
    $citas = Cita::with(['user', 'mascota'])->get();
    foreach ($citas as $cita) {
        echo "- ID: {$cita->id}, Usuario: {$cita->user?->name}, Mascota: {$cita->mascota?->nombre}\n";
    }
} else {
    echo "No hay citas en la BD\n";
}

// Crear una cita de prueba
echo "\n=== Creando cita de prueba ===\n";
$usuario = \App\Models\User::where('rol', 'cliente')->first();
$mascota = \App\Models\Mascota::first();

if ($usuario && $mascota) {
    $cita = Cita::create([
        'user_id' => $usuario->id,
        'mascota_id' => $mascota->id,
        'servicio' => 'Consulta General',
        'veterinario' => 'Dr. Juan',
        'fecha' => \Carbon\Carbon::today()->addDays(1),
        'hora' => '10:00',
        'motivo' => 'Revisión general de salud',
        'notas' => 'Cita de prueba',
        'status' => 'Próxima'
    ]);
    echo "✓ Cita creada: ID {$cita->id}\n";
} else {
    echo "No hay usuario o mascota disponible\n";
}
