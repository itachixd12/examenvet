<?php

// Simular un request al endpoint /api/admin/citas
require 'vendor/autoload.php';
require 'bootstrap/app.php';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

$app = require_once('bootstrap/app.php');
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

// Obtener primer usuario admin
$admin = \App\Models\User::where('rol', 'admin')->first();

if (!$admin) {
    echo "No se encontró usuario admin\n";
    exit(1);
}

echo "✓ Usuario admin encontrado: " . $admin->email . "\n";

// Obtener token
$token = $admin->createToken('admin-test')->plainTextToken;
echo "✓ Token generado: " . substr($token, 0, 20) . "...\n";

// Crear request simulado
$request = Request::create('/api/admin/citas', 'GET');
$request->headers->set('Authorization', 'Bearer ' . $token);

// Autenticar
Auth::guard('sanctum')->setUser($admin);

echo "\n=== Probando endpoint /api/admin/citas ===\n";

try {
    // Instanciar controller
    $controller = new \App\Http\Controllers\AdminController();
    $response = $controller->citasAdmin();
    
    $data = $response->getData(true);
    
    if (isset($data['citas'])) {
        echo "✓ Citas obtenidas: " . count($data['citas']) . " registros\n";
        foreach ($data['citas'] as $cita) {
            echo "  - Cita ID: " . $cita['id'] . ", Status: " . ($cita['status'] ?? 'N/A') . "\n";
        }
    } else {
        echo "⚠ Respuesta recibida pero sin estructura de citas:\n";
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    }
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
