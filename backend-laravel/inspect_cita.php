<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Cita;
use Illuminate\Support\Facades\DB;

echo "\n=== Inspeccionar Cita ===\n";

$cita = Cita::find(4);
echo "Cita 4:\n";
echo "  - ID: {$cita->id}\n";
echo "  - estado: '{$cita->estado}'\n";
echo "  - status: '{$cita->status}'\n";
echo "  - estado (raw): " . ($cita->getAttribute('estado') ?? 'NULL') . "\n";
echo "  - status (raw): " . ($cita->getAttribute('status') ?? 'NULL') . "\n";

echo "\nActualizar status a 'Próxima'...\n";
$cita->status = 'Próxima';
$cita->save();

$cita = Cita::find(4);
echo "Después:\n";
echo "  - status: '{$cita->status}'\n";
echo "  - estado: '{$cita->estado}'\n";
