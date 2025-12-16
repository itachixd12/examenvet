<?php
// Buscar las fotos de mascotas en la BD
echo "=== Verificar rutas de fotos en BD ===\n\n";

$host = 'localhost';
$user = 'root';
$pass = 'Ericksteven178.';
$db = 'proyecto';

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    echo "Error de conexión: " . mysqli_connect_error();
    exit(1);
}

$result = mysqli_query($conn, "SELECT id, nombre, foto FROM mascotas WHERE foto IS NOT NULL LIMIT 10");

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "ID: {$row['id']}, Nombre: {$row['nombre']}, Foto: {$row['foto']}\n";
    }
} else {
    echo "No hay mascotas con foto\n";
}

mysqli_close($conn);
