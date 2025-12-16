<?php
// Test what the API actually returns
require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Models\Mascota;

$mascotas = Mascota::with(['user' => function ($query) {
    $query->select('id', 'name', 'email');
}])
    ->orderBy('nombre')
    ->get();

echo "=== API Response Test ===\n\n";
foreach ($mascotas as $mascota) {
    echo "Mascota ID: {$mascota->id}\n";
    echo "Nombre: {$mascota->nombre}\n";
    echo "Foto (raw): " . json_encode($mascota->foto) . "\n";
    echo "Foto (toArray): " . json_encode($mascota->toArray()['foto'] ?? 'NULL') . "\n\n";
}

echo "\nFull JSON Response:\n";
echo json_encode(['data' => $mascotas], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>
