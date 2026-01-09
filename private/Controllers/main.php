<?php

include 'private/apps/InstantaneasControl.php';
$GLOBALS['TituloPagina'] = "Gekko";
$GLOBALS['Link'] = '<script src="{UrlBase}/public/js/reloj.js{VerCache}"></script>';
$GLOBALS['Link'] .= '<script src="{UrlBase}/public/js/gekko.js{VerCache}"></script>';
$GLOBALS['Contenido']= '';
$GLOBALS['layout'] = file_get_contents("private/vistas/layout.html");
echo IntegraLayout();
exit();