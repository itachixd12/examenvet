<?php
$conn = new \PDO('mysql:host=localhost;dbname=proyecto', 'root', 'Ericksteven178.');
$mascotas = $conn->query('SELECT id, nombre, foto FROM mascotas WHERE foto IS NOT NULL LIMIT 3')->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($mascotas, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>
