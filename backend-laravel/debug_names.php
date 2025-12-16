<?php
require 'vendor/autoload.php';

$conn = new mysqli('localhost', 'root', 'Ericksteven178.', 'proyecto');

if ($conn->connect_error) {
    die('Error: ' . $conn->connect_error);
}

echo "=== VETERINARIOS EN BD ===\n";
$result = $conn->query("SELECT id, nombre FROM veterinarios");
while ($row = $result->fetch_assoc()) {
    echo "ID: " . $row['id'] . " | Nombre: '" . $row['nombre'] . "'\n";
}

echo "\n=== SERVICIOS EN BD ===\n";
$result = $conn->query("SELECT id, nombre FROM servicios");
while ($row = $result->fetch_assoc()) {
    echo "ID: " . $row['id'] . " | Nombre: '" . $row['nombre'] . "'\n";
}

echo "\n=== ULTIMAS CITAS ===\n";
$result = $conn->query("SELECT id, veterinario, servicio FROM citas ORDER BY id DESC LIMIT 3");
while ($row = $result->fetch_assoc()) {
    echo "ID: " . $row['id'] . " | Vet guardado: '" . $row['veterinario'] . "' | Serv guardado: '" . $row['servicio'] . "'\n";
}

$conn->close();
?>
