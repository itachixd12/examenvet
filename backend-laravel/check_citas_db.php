<?php

require 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Console\Kernel;

// Crear aplicación
$app = new Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

$app
    ->singleton(
        Kernel::class,
        \App\Console\Kernel::class,
    );

// Obtener usuario admin
$admin = \App\Models\User::where('rol', 'admin')->first();

if (!$admin) {
    echo "✗ No se encontró usuario admin\n";
    exit(1);
}

echo "✓ Usuario admin encontrado: " . $admin->email . "\n";

// Obtener citas directamente del modelo
$citas = \App\Models\Cita::all();

echo "✓ Total de citas en base de datos: " . count($citas) . "\n";

if (count($citas) > 0) {
    echo "\nDetalles de citas:\n";
    foreach ($citas as $cita) {
        echo "  - ID: " . $cita->id 
            . ", Estado: " . $cita->estado 
            . ", Status: " . $cita->status 
            . "\n";
    }
} else {
    echo "⚠ No hay citas en la base de datos\n";
}
