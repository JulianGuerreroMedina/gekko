<?php

declare(strict_types=1);

function gekko_handler_main(): string
{
    include_once PRIVATE_PATH . '/apps/InstantaneasControl.php';
    return '';
}

function gekko_handler_admin(): string
{
    include_once PRIVATE_PATH . '/apps/adminControl.php';
    return (string) AdminControl();
}

function gekko_handler_error404(): void
{
    Pagina404();
}
