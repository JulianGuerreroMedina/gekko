<?php

$cadena = ''; 
/*------------------ pagina de Incio ------------------*/
include 'private/apps/DisplayContenido.php';
$contenido = '';
$layout = file_get_contents("private/vistas/layout.html");
echo IntegraLayout($layout, $link ='', 'Gekko', $contenido);
exit();