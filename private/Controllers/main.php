<?php

include APP_ROOT . '/private/apps/InstantaneasControl.php';
$GLOBALS['TituloPagina'] = "Gekko";
$GLOBALS['Link'] = '<script src="{Asset:/js/reloj.js}"></script>';
$GLOBALS['Link'] .= '<script src="{Asset:/js/gekko.js}"></script>';
$GLOBALS['Contenido']= '';
$GLOBALS['layout'] = file_get_contents(VIEWS_PATH . '/layout.html');
echo IntegraLayout();
exit();
