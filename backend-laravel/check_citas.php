<?php
require 'vendor/autoload.php';

$conn = new mysqli('localhost', 'root', 'Ericksteven178.', 'proyecto');

if ($conn->connect_error) {
    die('Error: ' . $conn->connect_error);
}

$result = $conn->query("SELECT id, mascota_id, veterinario_id, servicio_id, veterinario, servicio, fecha, hora FROM citas ORDER BY id DESC LIMIT 5");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "ID: " . $row['id'] . " | Mascota: " . $row['mascota_id'] . " | Vet ID: " . $row['veterinario_id'] . " | Vet: " . $row['veterinario'] . " | Serv ID: " . $row['servicio_id'] . " | Serv: " . $row['servicio'] . "\n";
    }
} else {
    echo "No hay citas";
}

$conn->close();
?>
