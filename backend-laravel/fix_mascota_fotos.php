<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

use App\Models\Mascota;

echo "=== Corrigiendo rutas de fotos de mascotas ===\n\n";

$mascotas = Mascota::whereNotNull('foto')->get();

foreach ($mascotas as $mascota) {
    $fotoActual = $mascota->foto;
    
    // Si ya tiene la ruta correcta (public/uploads/mascotas/), no hacer nada
    if (strpos($fotoActual, 'public/uploads/mascotas/') === 0) {
        echo "✓ {$mascota->nombre}: Ruta correcta\n";
        continue;
    }
    
    // Si solo tiene el nombre del archivo, agregar la ruta correcta
    if (strpos($fotoActual, '/') === false) {
        $rutaNueva = 'public/uploads/mascotas/' . $fotoActual;
        $mascota->foto = $rutaNueva;
        $mascota->save();
        echo "✓ {$mascota->nombre}: Actualizado de '{$fotoActual}' a '{$rutaNueva}'\n";
    } else {
        echo "? {$mascota->nombre}: Ruta desconocida: {$fotoActual}\n";
    }
}

echo "\n✓ Proceso completado\n";
