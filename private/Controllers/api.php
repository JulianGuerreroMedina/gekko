<?php

if (isset($GLOBALS['ruta_1']))
{
    $archivo_controlador = $GLOBALS['ruta_1'];
    $archivo_controlador = 'private/api/' . $archivo_controlador;
    if (file_exists($archivo_controlador . '.php')) 
    {
        include($archivo_controlador . '.php');
    }
    else
    {
        include 'private/Controllers/error404.php';
    }
}
else
{
    include 'private/Controllers/error404.php';
}