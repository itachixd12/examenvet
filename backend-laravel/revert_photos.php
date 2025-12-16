<?php
$conn = new PDO('mysql:host=localhost;dbname=proyecto', 'root', 'Ericksteven178.');
$stmt = $conn->prepare("UPDATE mascotas SET foto = REPLACE(foto, '/mascotas/', '/uploads/mascotas/') WHERE foto LIKE '/mascotas/%'");
$stmt->execute();
echo "BD revertida a /uploads/mascotas/\n";

$result = $conn->query("SELECT id, nombre, foto FROM mascotas WHERE foto IS NOT NULL");
foreach ($result as $row) {
    echo "{$row['nombre']}: {$row['foto']}\n";
}
?>
