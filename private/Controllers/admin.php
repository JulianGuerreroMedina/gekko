<?php
$link = '';
//ValidaUsuario(0);

if (empty($GLOBALS['ruta_1']))
{
    //VerPagina("xxxxx");
    include APP_ROOT . '/private/apps/adminControl.php';
    $GLOBALS['TituloPagina']="Administrador";
    $GLOBALS['Link'] = '<script src="{Asset:/js/admin.js}"></script>';
    $GLOBALS['Link'] .= '<script src="{Asset:/js/UploadFile.js}"></script>';
    $GLOBALS['Contenido']= AdminControl();
    $GLOBALS['layout'] = file_get_contents(VIEWS_PATH . '/layout_admin.html');
    echo IntegraLayout();
    exit();
} else
{
    error_log("[ERROR] " . __FILE__ . " Controlador cargado, pero la ruta no existe: " . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    Pagina404();
}
