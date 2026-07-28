<?php

declare(strict_types=1);

require_once PRIVATE_PATH . '/router/Middleware.php';
require_once PRIVATE_PATH . '/router/WebHandlers.php';
require_once PRIVATE_PATH . '/router/ApiHandlers.php';

const GEKKO_MAX_ROUTE_SEGMENTS = 10;

function gekko_obtener_segmentos(): array
{
    if (!isset($_GET['url'])) {
        return [];
    }

    $url = rtrim((string) $_GET['url'], '/');
    if ($url === '') {
        return [];
    }

    $url = filter_var($url, FILTER_SANITIZE_URL);
    if ($url === false || $url === '') {
        return [];
    }

    return array_values(
        array_filter(
            array_map('trim', explode('/', $url)),
            static fn(string $segmento): bool => $segmento !== ''
        )
    );
}

function gekko_poblar_segmentos(array $segmentos): void
{
    for ($indice = 0; $indice < GEKKO_MAX_ROUTE_SEGMENTS; $indice++) {
        $GLOBALS['ruta_' . $indice] = $segmentos[$indice] ?? '';
    }
}

function gekko_segmento_valido(string $segmento): bool
{
    return $segmento !== '' && preg_match('/^[a-zA-Z0-9_-]+$/', $segmento) === 1;
}

function gekko_rutas_web(): array
{
    return [
        '' => [
            'handler' => 'gekko_handler_main',
            'titulo' => 'Gekko',
            'link' => '<script src="{Asset:/js/reloj.js}"></script>'
                . '<script src="{Asset:/js/gekko.js}"></script>',
            'layout' => VIEWS_PATH . '/layout.html',
            'auth' => false,
        ],
        'main' => [
            'handler' => 'gekko_handler_main',
            'titulo' => 'Gekko',
            'link' => '<script src="{Asset:/js/reloj.js}"></script>'
                . '<script src="{Asset:/js/gekko.js}"></script>',
            'layout' => VIEWS_PATH . '/layout.html',
            'auth' => false,
        ],
        'admin' => [
            'handler' => 'gekko_handler_admin',
            'titulo' => 'Administrador',
            'link' => '<script src="{Asset:/js/admin.js}"></script>'
                . '<script src="{Asset:/js/UploadFile.js}"></script>',
            'layout' => VIEWS_PATH . '/layout_admin.html',
            'auth' => false,
        ],
    ];
}

function gekko_despachar(): void
{
    $segmentos = gekko_obtener_segmentos();
    gekko_poblar_segmentos($segmentos);

    foreach ($segmentos as $segmento) {
        if (!gekko_segmento_valido($segmento)) {
            gekko_handler_error404();
            return;
        }
    }

    $rutaPrincipal = (string) ($GLOBALS['ruta_0'] ?? '');

    if ($rutaPrincipal === 'salir') {
        Salir();
        return;
    }

    if ($rutaPrincipal === 'api') {
        csrf_reject_invalid_api_post();

        $controlador = (string) ($GLOBALS['ruta_1'] ?? '');
        if ($controlador === '' || count($segmentos) !== 2) {
            gekko_handler_error404();
            return;
        }

        $rutasApi = [
            'admin' => ['auth' => false],
            'instantaneas' => ['auth' => false],
        ];
        $rutaApi = $rutasApi[$controlador] ?? null;
        if ($rutaApi === null || !gekko_middleware_api($rutaApi)) {
            if ($rutaApi === null) {
                gekko_handler_error404();
            }
            return;
        }

        gekko_manejar_api($controlador);
        return;
    }

    $ruta = gekko_rutas_web()[$rutaPrincipal] ?? null;
    if ($ruta === null || count($segmentos) > 1) {
        gekko_handler_error404();
        return;
    }

    gekko_render_web($ruta, $ruta['handler']);
}

gekko_despachar();
