<?php

// Crear las contraseñas hasheadas correctamente
$admin_password = password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 12]);
$user_password = password_hash('usuario123', PASSWORD_BCRYPT, ['cost' => 12]);

echo "Contraseña Admin: " . $admin_password . "\n";
echo "Contraseña Usuario: " . $user_password . "\n";

// Actualizar en la base de datos
$servername = "127.0.0.1";
$username = "root";
$password = "Ericksteven178.";
$dbname = "proyecto";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Actualizar contraseña del admin
$sql1 = "UPDATE users SET password = '$admin_password' WHERE email = 'admin@petcare.com'";
$sql2 = "UPDATE users SET password = '$user_password' WHERE email = 'usuario@petcare.com'";

if ($conn->query($sql1) === TRUE && $conn->query($sql2) === TRUE) {
    echo "Contraseñas actualizadas correctamente\n";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
