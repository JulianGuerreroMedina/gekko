<?php

function LogAccesoSistema()
{
    $id_usuario = $_SESSION['ID_USUARIO'];
    $nombre_bd = $_ENV['URL_LOGS'] . date('Y') . '_' . $_ENV['MIAPP'] . '_log.sqlite';
    if (!(file_exists("$nombre_bd"))) {
        bdLogSqlite($nombre_bd);
    }
    $ip = $_SERVER['REMOTE_ADDR'];
    $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
    $tiempo = date('Y-m-d H:m:s');
    $usuario = $_SESSION['NombreUsuario'];
    $bd = new SQLite3($nombre_bd);
    $consulta_sql = "INSERT INTO logs VALUES(NULL, 1, '$tiempo', '$ip', '$id_usuario', '$usuario', '$HTTP_USER_AGENT', '', '');";
    $bd->query($consulta_sql);
}

function LogRequest_URI()
{
    $nombre_bd = $_ENV['URL_LOGS'] . date('Y') . '_' . $_ENV['MIAPP'] . '_log.sqlite';
    if (!(file_exists("$nombre_bd"))) {
        bdLogSqlite($nombre_bd);
    }
    $ip = $_SERVER['REMOTE_ADDR'];
    $REQUEST_URI = $GLOBALS['OrigenURL'] . $_SERVER['REQUEST_URI'];
    $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
    $tiempo =  date('Y-m-d H:m:s');
    $id_usuario = IdUsuario();
    $usuario = $_SESSION['NombreUsuario'];
    $bd = new SQLite3($nombre_bd);
    $consulta_sql = "INSERT INTO logs VALUES(NULL, 2, '$tiempo', '$ip', '$id_usuario', '$usuario', '$HTTP_USER_AGENT', '$REQUEST_URI','');";
    $bd->query($consulta_sql);
}

function LogDescargaExpediente($url_archivo)
{
    $id_usuario = IdUsuario();
    $nombre_bd = $_ENV['URL_LOGS'] . date('Y') . '_' . $_ENV['MIAPP'] . '_log.sqlite';
    if (!(file_exists("$nombre_bd"))) {
        bdLogSqlite($nombre_bd);
    }
    $ip = $_SERVER['REMOTE_ADDR'];
    $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
    $REQUEST_URI = $GLOBALS['OrigenURL'] . $_SERVER['REQUEST_URI'];
    $tiempo = date('Y-m-d H:m:s');
    $usuario = $_SESSION['NombreUsuario'];
    $url_archivo = $GLOBALS['OrigenURL'] . "/" . $url_archivo;
    $bd = new SQLite3($nombre_bd);
    $consulta_sql = "INSERT INTO logs VALUES(NULL, 3, '$tiempo', '$ip', '$id_usuario', '$usuario', '$HTTP_USER_AGENT', '', '$url_archivo');";
    $bd->query($consulta_sql);
}

function bdLogSqlite($nombre_bd)
{
    $bd = new SQLite3($nombre_bd);
    $consulta_sql = "CREATE TABLE logs (id INTEGER PRIMARY KEY, tipo NUMERIC, fecha DATETIME, ip TEXT, id_usuario NUMERIC, usuario TEXT, navegador TEXT, url TEXT, archivo TEXT);";
    $bd->query($consulta_sql);
}
