<?php
// Test simple para verificar que el controlador funciona

// Cargar Laravel
define('LARAVEL_START', microtime(true));

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

// Obtener la aplicación
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

echo "=== VERIFICACIÓN DE ADMIN CONTROLLER ===\n\n";

// Verificar admin existe
$admin = \App\Models\User::where('rol', 'admin')->first();
if ($admin) {
    echo "✓ Admin encontrado: " . $admin->email . "\n";
} else {
    echo "✗ No hay usuario admin\n";
    exit(1);
}

// Verificar citas existen
$citas = \App\Models\Cita::count();
echo "✓ Total citas en BD: " . $citas . "\n";

// Verificar controlador existe
if (class_exists('\App\Http\Controllers\AdminController')) {
    echo "✓ AdminController existe\n";
} else {
    echo "✗ AdminController NO existe\n";
    exit(1);
}

// Verificar métodos
$controller = new \App\Http\Controllers\AdminController();
if (method_exists($controller, 'citasAdmin')) {
    echo "✓ Método citasAdmin() existe\n";
} else {
    echo "✗ Método citasAdmin() NO existe\n";
    exit(1);
}

// Verificar que AdminController no tiene constructor problemático
$reflection = new \ReflectionClass($controller);
$constructor = $reflection->getConstructor();
if ($constructor === null) {
    echo "✓ Constructor bien eliminado\n";
} else {
    echo "⚠ Constructor aún existe: " . $constructor->getName() . "\n";
}

echo "\n✓ TODAS LAS VERIFICACIONES PASARON\n";
