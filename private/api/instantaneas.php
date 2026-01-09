<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    error_log("[ERROR_REQUEST_METHOD] " . __FILE__ . " - Error en Metodo de Request diferente a POST");
    echo json_encode(['error' => 'Método no permitido']);
    return;
}

$funcion = trim($_POST['funcion'] ?? '');

switch ($funcion) {

    case 'Instantaneas':
        include 'private/apps/InstantaneasControl.php';
        print json_encode(Instantaneas());
        return;

    default:
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Función no soportada']);
        return;
}
