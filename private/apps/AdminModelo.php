<?php
function bdBorrarMultimedia($id_multimedia)
{
    $bd = Db::getInstancia();
    $consulta_sql = "DELETE FROM multimedia 
    WHERE multimedia.id = $id_multimedia";
    $bd->ejecutar($consulta_sql);
}

function bdAgregarMultimedia($tipo, $descripcion, $duracion, $f_inicio, $f_final, $h_inicio, $h_final)
{
    $bd = Db::getInstancia();
    $consulta_sql = "INSERT INTO multimedia (
    multimedia.tipo, 
    multimedia.descripcion, 
    multimedia.duracion, 
    multimedia.f_inicio, 
    multimedia.f_final, 
    multimedia.h_inicio, 
    multimedia.h_final 
    ) 
    VALUES (
        '$tipo',
        '$descripcion',
        '$duracion',
        '$f_inicio',
        '$f_final',
        '$h_inicio',
        '$h_final'
    )";
    $bd->ejecutar($consulta_sql);
    return $bd->lastInsertId();
}


function bdModificarMultimedia($id_multimedia, $tipo, $descripcion, $duracion, $f_inicio, $f_final, $h_inicio, $h_final)
{
    $bd = Db::getInstancia();
    $consulta_sql = "UPDATE multimedia 
    SET multimedia.tipo = '$tipo',
    multimedia.descripcion = '$descripcion',
    multimedia.duracion = '$duracion',
    multimedia.f_inicio = '$f_inicio',        
    multimedia.f_final = '$f_final',
    multimedia.h_inicio = '$h_inicio',        
    multimedia.h_final = '$h_final'
    WHERE multimedia.id = $id_multimedia";
    $bd->ejecutar($consulta_sql);
}

function bdSetMultimedia($id_multimedia, $estado)
{
    $bd = Db::getInstancia();
    $consulta_sql = "UPDATE multimedia 
    SET multimedia.activo = $estado
    WHERE multimedia.id = $id_multimedia";
    $bd->ejecutar($consulta_sql);
}


function bdMultimediaPorId($id_multimedia)
{
    $bd = Db::getInstancia();
    $consulta_sql = "SELECT multimedia.id,
    multimedia.tipo,
    multimedia.descripcion,
    multimedia.url,
    multimedia.duracion,
    multimedia.f_inicio,        
    multimedia.f_final,
    multimedia.h_inicio,        
    multimedia.h_final,
    multimedia.activo
    FROM multimedia 
    WHERE multimedia.id = $id_multimedia
    LIMIT 0,1";
    $resultado = $bd->ejecutar($consulta_sql);
    $datos = array();
    while ($fila = $bd->obtener_fila($resultado, 0)) {
        $datos[] = $fila;
    }
    return $datos;
}

function bdListaMultimedia()
{
    $bd = Db::getInstancia();
    $consulta_sql = "SELECT multimedia.id,
    multimedia.tipo,
    multimedia.descripcion,
    multimedia.url,
    multimedia.duracion,
    multimedia.f_inicio,
    multimedia.f_final,
    multimedia.h_inicio,
    multimedia.h_final,
    multimedia.activo
    FROM multimedia 
    ORDER BY multimedia.activo DESC, multimedia.id DESC";
    $resultado = $bd->ejecutar($consulta_sql);
    $datos = array();
    while ($fila = $bd->obtener_fila($resultado, 0)) {
        $datos[] = $fila;
    }
    return $datos;
}
