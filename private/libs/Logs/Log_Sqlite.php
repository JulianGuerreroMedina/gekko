<?php



function LogGeneraBDPagina404($nombre_bd)
{
    $bd = new SQLite3($nombre_bd); 
    $consulta_sql = "CREATE TABLE error404 (id INTEGER PRIMARY KEY, fecha DATETIME, ip TEXT, navegador TEXT, url TEXT);"; 
    $bd->query($consulta_sql); 
}

function LogPagina404()
{
    $anio= date('Y'); 
    $nombre_bd="logs/".$anio."_log.404.sqlite"; 
    if (!(file_exists("$nombre_bd")))
    {
        LogGeneraBDPagina404($nombre_bd); 
    }
    $url=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $ip=$_SERVER['REMOTE_ADDR']; 
    $HTTP_USER_AGENT=$_SERVER['HTTP_USER_AGENT'];
    $tiempo=  date('Y-m-d H:m:s'); 
    $bd = new SQLite3($nombre_bd); 
    $consulta_sql = "INSERT INTO error404 VALUES(NULL,'$tiempo','$ip','$HTTP_USER_AGENT','$url');"; 
    $bd->query($consulta_sql); 
}
