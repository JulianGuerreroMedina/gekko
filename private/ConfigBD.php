<?php

define ('bd_dbname', 'data/gekko.sqlite');


if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on') {
    $GLOBALS['protocolo'] ="http://";
}
else
if (isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'on') {
    $GLOBALS['protocolo'] ="https://";
}

$GLOBALS['OrigenURL'] = $GLOBALS['protocolo'].$_SERVER['HTTP_HOST']."";
$GLOBALS['CADENASESSID'] = "dfegs56";
$GLOBALS['copy'] = "\n<!-- Gekko - Desarrollado por Julian Guerrero Medina &copy; 2011 - ".date('Y')." -->";

$GLOBALS['desarrollo'] = 1;

