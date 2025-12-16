<?php
require 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

// Configurar la base de datos
$db = new DB;
$db->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'proyecto',
    'username'  => 'root',
    'password'  => 'Ericksteven178.',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
]);

$db->setAsGlobal();
$db->bootEloquent();

// Actualizar todas las citas que no tengan status
$updated = DB::table('citas')
    ->whereNull('status')
    ->update(['status' => 'Próxima']);

echo "✓ $updated citas actualizadas con status 'Próxima'\n";

// Mostrar el total de citas
$total = DB::table('citas')->count();
echo "✓ Total de citas en la base de datos: $total\n";
