<?php

function ValidaBD()
{
    if (!(file_exists($_ENV['BD_DBNAME'])))
    {
        $bd = new SQLite3($_ENV['BD_DBNAME']); 
        $consulta_sql = "CREATE TABLE multimedia (id INTEGER, tipo INTEGER DEFAULT 1, descripcion TEXT DEFAULT '', url TEXT, duracion INTEGER DEFAULT 30, f_inicio DATE, f_final DATE, activo INTEGER DEFAULT 1, PRIMARY KEY(id AUTOINCREMENT))";
        $bd -> query($consulta_sql); 
    
        $consulta_sql = "CREATE TABLE user (id INTEGER, usuario TEXT, pass TEXT, admin INTEGER DEFAULT 0, abm INTEGER DEFAULT 1, activo	INTEGER DEFAULT 1, PRIMARY KEY(id AUTOINCREMENT))";
        $bd -> query($consulta_sql); 
    
        $consulta_sql = "INSERT INTO user VALUES(NULL,'admin', 'admin', '1', '1', '1')"; 
        $bd -> query($consulta_sql); 

        $bd->close();
    }
}