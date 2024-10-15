<?php
error_log('Inicio del script PHP');
$directorio = 'resources/Usuario';
$archivos = array_diff(scandir($directorio), array('..', '.'));

// Verifica si hay archivos antes de generar las rutas
if (!empty($archivos)) {
    $rutas = array_map(function($archivo) use ($directorio) {
        return $directorio . '/' . $archivo;
    }, $archivos);

    header('Content-Type: application/json');
    echo json_encode(array_values($rutas));
} else {
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'No se encontraron archivos en el directorio.'));
}
?>
