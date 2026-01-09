<?php

//ValidaUsuario(1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    error_log("[ERROR_REQUEST_METHOD] " . __FILE__ . " - Error en Metodo de Request diferente a POST");
    echo json_encode(['error' => 'Método no permitido']);
    return;
}

$funcion = trim($_POST['funcion'] ?? '');

switch ($funcion) {
    
    case 'ProcesaFrmBorrarMultimedia':
        include 'private/apps/ABM_multimedia.php';
        echo json_encode(ProcesaFrmBorrarMultimedia());
        return;

    case 'frmSetear':
        include 'private/apps/ABM_multimedia.php';
        echo (frmSetear());
        return;

    case 'ListaMultimedia':
        include 'private/apps/adminControl.php';
        echo json_encode(ListaMultimedia());
        return;

    case 'ProcesaFrmAbmMultimedia':
        include 'private/apps/ABM_multimedia.php';
        echo json_encode(ProcesaFrmAbmMultimedia());
        return;

    case 'FrmAbmMultimedia':
        include 'private/apps/ABM_multimedia.php';
        echo (FrmAbmMultimedia());
        return;

    case 'ProcesaFormAgregarSustento':
        include 'private/apps/CargarArchivoMultimedia.php';
        echo json_encode(ProcesaFormAgregarSustento());
        return;

    case 'FormAgregarSustento':
        include 'private/apps/CargarArchivoMultimedia.php';
        echo (FormAgregarSustento());
        return;

    default:
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Función no soportada']);
        return;
}