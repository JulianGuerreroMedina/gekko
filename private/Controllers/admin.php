<?php
$link = '';
if (isset($GLOBALS['ruta_0']) == 'admin' )
{
    include 'private/apps/adminControl.php';
    $GLOBALS['TituloPagina']="Administrador";
    $GLOBALS['Link'] = '<script src="{UrlBase}/public/js/admin.js{VerCache}"></script>';
    $GLOBALS['Link'] .= '<script src="{UrlBase}/public/js/UploadFile.js{VerCache}"></script>';
    $GLOBALS['Contenido']= AdminControl();
    
    $layout = file_get_contents("private/vistas/layout_admin.html");
    echo IntegraLayout($layout, $link, 'Administrador', $GLOBALS['Contenido']);
}
else
{
    include 'private/apps/adminControl.php';
}
