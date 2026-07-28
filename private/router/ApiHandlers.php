<?php

declare(strict_types=1);

/**
 * Registra las acciones API sin mover la lógica de los módulos existentes.
 * raw=true conserva las respuestas HTML históricas de algunas acciones.
 *
 * @return array<string,array<string,array{handler:callable,raw:bool}>>
 */
function gekko_api_acciones(): array
{
    return [
        'admin' => [
            'ProcesaFrmBorrarMultimedia' => [
                'handler' => static function (): mixed {
                    include_once PRIVATE_PATH . '/apps/ABM_multimedia.php';
                    return ProcesaFrmBorrarMultimedia();
                },
                'raw' => false,
            ],
            'frmSetear' => [
                'handler' => static function (): mixed {
                    include_once PRIVATE_PATH . '/apps/ABM_multimedia.php';
                    return frmSetear();
                },
                'raw' => true,
            ],
            'ListaMultimedia' => [
                'handler' => static function (): mixed {
                    include_once PRIVATE_PATH . '/apps/adminControl.php';
                    return ListaMultimedia();
                },
                'raw' => false,
            ],
            'ProcesaFrmAbmMultimedia' => [
                'handler' => static function (): mixed {
                    include_once PRIVATE_PATH . '/apps/ABM_multimedia.php';
                    return ProcesaFrmAbmMultimedia();
                },
                'raw' => false,
            ],
            'FrmAbmMultimedia' => [
                'handler' => static function (): mixed {
                    include_once PRIVATE_PATH . '/apps/ABM_multimedia.php';
                    return FrmAbmMultimedia();
                },
                'raw' => true,
            ],
            'ProcesaFormAgregarSustento' => [
                'handler' => static function (): mixed {
                    include_once PRIVATE_PATH . '/apps/CargarArchivoMultimedia.php';
                    return ProcesaFormAgregarSustento();
                },
                'raw' => false,
            ],
            'FormAgregarSustento' => [
                'handler' => static function (): mixed {
                    include_once PRIVATE_PATH . '/apps/CargarArchivoMultimedia.php';
                    return FormAgregarSustento();
                },
                'raw' => true,
            ],
        ],
        'instantaneas' => [
            'Instantaneas' => [
                'handler' => static function (): mixed {
                    include_once PRIVATE_PATH . '/apps/InstantaneasControl.php';
                    return Instantaneas();
                },
                'raw' => false,
            ],
        ],
    ];
}

function gekko_manejar_api(string $controlador): void
{
    $funcion = trim((string) ($_POST['funcion'] ?? ''));
    $acciones = gekko_api_acciones();
    $accion = $acciones[$controlador][$funcion] ?? null;

    if ($accion === null) {
        gekko_responder_json(['error' => 'Función no soportada']);
        return;
    }

    $respuesta = ($accion['handler'])();
    if ($accion['raw'] === true) {
        echo (string) $respuesta;
        return;
    }

    gekko_responder_json($respuesta);
}
