<?php

declare(strict_types=1);

/**
 * Middleware transversal del router de Gekko.
 *
 * Las rutas se mantienen sin autenticacion por compatibilidad con el
 * comportamiento actual. El parametro auth queda disponible para activar
 * ValidaUsuario() en una etapa separada de hardening.
 *
 * @param array<string,mixed> $ruta
 */
function gekko_middleware_web(array $ruta): void
{
    if (($ruta['auth'] ?? false) === true) {
        ValidaUsuario(0);
    }

    $permiso = trim((string) ($ruta['permiso'] ?? ''));
    if ($permiso !== '') {
        VerPagina($permiso);
    }
}

function gekko_middleware_api(array $ruta): bool
{
    if (($ruta['auth'] ?? false) === true) {
        ValidaUsuario(1);
    }

    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        http_response_code(405);
        gekko_responder_json(['error' => 'Método no permitido']);
        return false;
    }

    return true;
}

function gekko_responder_json(mixed $respuesta): void
{
    header('Content-Type: application/json');
    echo json_encode($respuesta);
}

/**
 * @param array<string,mixed> $ruta
 * @param callable(): string $handler
 */
function gekko_render_web(array $ruta, callable $handler): void
{
    gekko_middleware_web($ruta);

    $GLOBALS['TituloPagina'] = (string) ($ruta['titulo'] ?? 'Gekko');
    $GLOBALS['Link'] = (string) ($ruta['link'] ?? '');
    $GLOBALS['Contenido'] = $handler();
    $GLOBALS['layout'] = file_get_contents(
        (string) ($ruta['layout'] ?? VIEWS_PATH . '/layout.html')
    );

    echo IntegraLayout();
}
