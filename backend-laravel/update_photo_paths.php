<?php
$conn = new PDO('mysql:host=localhost;dbname=proyecto', 'root', 'Ericksteven178.');

echo "=== Actualizando rutas de fotos ===\n\n";

// Primero, ver lo actual
$result = $conn->query("SELECT id, nombre, foto FROM mascotas WHERE foto IS NOT NULL");
echo "Rutas ANTES:\n";
foreach ($result as $row) {
    echo "{$row['nombre']}: {$row['foto']}\n";
}

// Actualizar las rutas
$stmt = $conn->prepare("UPDATE mascotas SET foto = ? WHERE id = ?");
$mascotas = $conn->query("SELECT id, foto FROM mascotas WHERE foto IS NOT NULL")->fetchAll();

foreach ($mascotas as $m) {
    $oldFoto = $m['foto'];
    $newFoto = str_replace('/uploads/mascotas/', '/mascotas/', $oldFoto);
    
    if ($oldFoto !== $newFoto) {
        $stmt->execute([$newFoto, $m['id']]);
        echo "Actualizado: {$oldFoto} -> {$newFoto}\n";
    }
}

// Verificar
echo "\nRutas DESPUÉS:\n";
$result = $conn->query("SELECT id, nombre, foto FROM mascotas WHERE foto IS NOT NULL");
foreach ($result as $row) {
    echo "{$row['nombre']}: {$row['foto']}\n";
}
?>
