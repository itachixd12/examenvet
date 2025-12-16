<?php
// Simular el request al API admin/mascotas-global
require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Models\Mascota;

echo "=== Test API Admin Mascotas ===\n\n";

$mascotas = Mascota::with(['user' => function ($query) {
    $query->select('id', 'name', 'email');
}])
    ->orderBy('nombre')
    ->get();

echo "Total mascotas: " . count($mascotas) . "\n\n";

foreach ($mascotas as $m) {
    echo "ID: {$m->id}\n";
    echo "Nombre: {$m->nombre}\n";
    echo "Foto (raw): " . ($m->foto ?? 'NULL') . "\n";
    echo "---\n";
}

echo "\nJSON Response (what API sends):\n";
$response = response()->json(['data' => $mascotas]);
echo $response->getContent();
?>
