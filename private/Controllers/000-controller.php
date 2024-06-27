<?php

$url = array();

if (isset($_GET['url'])) {
    $url = $_GET['url'];
    $url = rtrim($url, '/');
    $url = filter_var($url, FILTER_SANITIZE_URL);
    $partes = explode('/', $url);
    // Listar cada parte obtenida
    $id = 0;
    foreach ($partes as $parte) {
        // print "GLOBALS['ruta_".$id."'] = $parte" . '<br>';
        $GLOBALS['ruta_'. $id] = $parte;
        $id += 1;
    }
} 

if (!(isset($GLOBALS['ruta_0']))) 
{   /*------------------ pagina de Incio ------------------*/
    include 'private/apps/DisplayContenido.php';
    $contenido = '';
    $layout = file_get_contents("private/vistas/layout.html");
    echo IntegraLayout($layout, $link ='', 'Gekko', $contenido);
} 
else
if (isset($GLOBALS['ruta_0'])) 
{
    $archivo_controlador = 'private/Controllers/' . $GLOBALS['ruta_0'] ;
    if (file_exists($archivo_controlador . '.php')) 
    {
        include($archivo_controlador . '.php');
    } 
    else 
    {
        include 'private/Controllers/error404.php';
    }
}
