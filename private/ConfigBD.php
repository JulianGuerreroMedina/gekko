<?php

define ('bd_dbname', 'data/gekko.sqlite');
define('debug',  1); // 1 para depurar, 0 para producción

if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on') {
    $GLOBALS['protocolo'] ="http://";
}
else
if (isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'on') {
    $GLOBALS['protocolo'] ="https://";
}

$GLOBALS['OrigenURL'] = $GLOBALS['protocolo'].$_SERVER['HTTP_HOST']."";
$GLOBALS['TOKENSESSID'] = "9618sds";
$GLOBALS['TiempoInactivoEnMinutos'] = 60; //30 /60
$GLOBALS['TiempoInactivo'] = $GLOBALS['TiempoInactivoEnMinutos'] * 60;
$GLOBALS['MinimizaLayout'] = 11;/* (1) Minimiza el codigo html para el cliente;  */
$GLOBALS['URL_Login'] = $GLOBALS['OrigenURL']."/admin/";
$GLOBALS['URL_Post_Login'] = $GLOBALS['OrigenURL']."/";
$GLOBALS['copy'] = "\n<!-- Gekko - Desarrollado por Julian Guerrero Medina &copy; 2011 - ".date('Y')." -->";