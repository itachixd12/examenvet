<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Cita;

echo "\n=== Normalizando Status de Citas ===\n";

try {
    // Actualizar citas con status en minúsculas
    Cita::where('status', 'like', 'pendiente')->update(['status' => 'Próxima']);
    Cita::where('status', 'like', 'confirmada')->update(['status' => 'Próxima']);
    Cita::where('status', 'like', 'completada')->update(['status' => 'Completada']);
    Cita::where('status', 'like', 'cancelada')->update(['status' => 'Cancelada']);
    
    // También sincronizar el campo estado
    $citas = Cita::all();
    foreach ($citas as $cita) {
        if ($cita->status && !$cita->estado) {
            $cita->estado = $cita->status;
            $cita->save();
        }
    }
    
    echo "✓ Citas normalizadas\n";
    
    // Mostrar citas actualizadas
    $citas = Cita::all();
    foreach ($citas as $cita) {
        echo "  - Cita {$cita->id}: status='{$cita->status}', estado='{$cita->estado}'\n";
    }
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
