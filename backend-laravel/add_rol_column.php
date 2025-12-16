<?php

$conn = new mysqli('127.0.0.1', 'root', 'Ericksteven178.', 'proyecto');

if ($conn->connect_error) {
    die('Error de conexión: ' . $conn->connect_error);
}

$sql = 'ALTER TABLE users ADD COLUMN rol VARCHAR(255) DEFAULT "cliente" AFTER updated_at';

if ($conn->query($sql) === TRUE) {
    echo "Columna rol agregada correctamente\n";
} else {
    if ($conn->errno == 1060) {
        echo "La columna rol ya existe\n";
    } else {
        echo "Error: " . $conn->error . "\n";
    }
}

$conn->close();
?>
