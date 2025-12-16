<?php

// Crear el enlace simbólico para las imágenes

$linkPath = __DIR__ . '/public/storage';
$targetPath = __DIR__ . '/storage/app/public';

// Verificar si el enlace ya existe
if (is_link($linkPath)) {
    echo "El enlace simbólico ya existe\n";
} else if (is_dir($linkPath)) {
    echo "El directorio ya existe\n";
} else {
    // Crear el enlace simbólico
    if (symlink($targetPath, $linkPath)) {
        echo "Enlace simbólico creado exitosamente\n";
    } else {
        echo "Error al crear el enlace simbólico\n";
        echo "Intenta ejecutar: mklink /D \"$linkPath\" \"$targetPath\"\n";
    }
}

?>
