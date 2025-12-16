<?php

$conn = new mysqli('127.0.0.1', 'root', 'Ericksteven178.', 'proyecto');

if ($conn->connect_error) {
    die('Error de conexión: ' . $conn->connect_error);
}

// Verificar si las columnas ya existen
$checkEspecie = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'citas' AND COLUMN_NAME = 'especie'";
$resultEspecie = $conn->query($checkEspecie);

$checkServicio = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'citas' AND COLUMN_NAME = 'servicio'";
$resultServicio = $conn->query($checkServicio);

$checkVeterinario = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'citas' AND COLUMN_NAME = 'veterinario'";
$resultVeterinario = $conn->query($checkVeterinario);

// Agregar columnas si no existen
$sql = '';

if ($resultEspecie->num_rows == 0) {
    $sql .= 'ALTER TABLE citas ADD COLUMN especie VARCHAR(255) AFTER mascota_id; ';
}

if ($resultServicio->num_rows == 0) {
    $sql .= 'ALTER TABLE citas ADD COLUMN servicio VARCHAR(255) AFTER servicio_id; ';
}

if ($resultVeterinario->num_rows == 0) {
    $sql .= 'ALTER TABLE citas ADD COLUMN veterinario VARCHAR(255) AFTER veterinario_id; ';
}

if ($sql) {
    if ($conn->multi_query($sql)) {
        do {
            if ($conn->more_results()) {
                $conn->next_result();
            }
        } while ($conn->more_results());
        echo "Columnas agregadas correctamente\n";
    } else {
        echo "Error: " . $conn->error . "\n";
    }
} else {
    echo "Las columnas ya existen\n";
}

$conn->close();
?>
