<?php
$link = '';
//ValidaUsuario(0);

if (empty($GLOBALS['ruta_1']))
{
    //VerPagina("xxxxx");
    include 'private/apps/adminControl.php';
    $GLOBALS['TituloPagina']="Administrador";
    $GLOBALS['Link'] = '<script src="{UrlBase}/public/js/admin.js{VerCache}"></script>';
    $GLOBALS['Link'] .= '<script src="{UrlBase}/public/js/UploadFile.js{VerCache}"></script>';
    $GLOBALS['Contenido']= AdminControl();
    $GLOBALS['layout'] = file_get_contents("private/vistas/layout_admin.html");
    echo IntegraLayout();
    exit();
} else
{
    error_log("[ERROR] " . __FILE__ . " Controlador cargado, pero la ruta no existe: " . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    Pagina404();
}
