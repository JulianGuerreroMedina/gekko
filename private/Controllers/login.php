<?php

if (isset($GLOBALS['ruta_0']) == 'login' )
{
    $GLOBALS['TituloPagina']="Login";
    $GLOBALS['Contenido']= 'Login de Usuario ' . time();
    include 'private/apps/DisplayContenido.php';
    $layout = file_get_contents("private/vistas/LayoutLogin.html");
    echo IntegraLayout($layout, $link ='', 'Administrador', '');
}
else
{
    include 'private/apps/adminControl.php';
}