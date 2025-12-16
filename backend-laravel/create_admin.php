<?php
require 'vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Datos del administrador
$adminEmail = 'admin@petcare.com';
$adminPassword = 'admin123';
$adminName = 'Administrador';

try {
    // Verificar si el admin ya existe
    $existingAdmin = User::where('email', $adminEmail)->first();
    
    if ($existingAdmin) {
        echo "✓ El usuario administrador ya existe: $adminEmail\n";
        echo "Rol actual: " . $existingAdmin->rol . "\n";
        
        // Actualizar a admin si existe pero no tiene el rol
        if ($existingAdmin->rol !== 'admin') {
            $existingAdmin->update(['rol' => 'admin']);
            echo "✓ Rol actualizado a 'admin'\n";
        }
    } else {
        // Crear nuevo usuario admin
        $admin = User::create([
            'name' => $adminName,
            'email' => $adminEmail,
            'password' => Hash::make($adminPassword),
            'rol' => 'admin',
            'email_verified_at' => now(),
        ]);

        echo "✓ Administrador creado exitosamente!\n";
        echo "Email: $adminEmail\n";
        echo "Contraseña: $adminPassword\n";
        echo "Rol: admin\n";
    }

} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
