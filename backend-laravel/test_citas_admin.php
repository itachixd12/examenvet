<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Cita;

echo "\n=== Prueba de Citas Admin ===\n";

try {
    // Prueba 1: Contar citas
    $total = Cita::count();
    echo "✓ Total de citas: " . $total . "\n";
    
    // Prueba 2: Obtener citas con relaciones
    $citas = Cita::with(['user' => function($q) {
        $q->select('id', 'name', 'email');
    }, 'mascota' => function($q) {
        $q->select('id', 'nombre', 'especie');
    }])
    ->orderBy('fecha', 'desc')
    ->get();
    
    echo "✓ Citas cargadas: " . count($citas) . "\n";
    
    if (count($citas) > 0) {
        foreach ($citas as $cita) {
            echo "\n  - Cita ID: {$cita->id}\n";
            echo "    Usuario: " . ($cita->user ? $cita->user->name : 'NULL') . "\n";
            echo "    Mascota: " . ($cita->mascota ? $cita->mascota->nombre : 'NULL') . "\n";
            echo "    Status: {$cita->status}\n";
        }
    }
    
    echo "\n✓ Todo funcionó correctamente\n";
} catch (\Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
