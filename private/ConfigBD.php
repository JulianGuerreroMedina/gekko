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

if ($GLOBALS['desarrollo'] == 1)
{
    ini_set('display_errors',1);
    error_reporting(E_ALL);
    $GLOBALS['DepuraSQL'] = 10;
}
