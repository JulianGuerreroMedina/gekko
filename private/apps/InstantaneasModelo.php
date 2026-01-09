<?php

function bdInstantaneas($hoy, $ahora)
{
    $bd = Db::getInstancia();
    $consulta_sql = "SELECT multimedia.tipo,
        multimedia.url,
        multimedia.duracion
        FROM multimedia
        WHERE multimedia.url <> ''
        AND multimedia.activo = 1 
        AND DATE(multimedia.f_inicio) <= '$hoy' 
        AND DATE(multimedia.f_final) >= '$hoy'
		AND multimedia.h_inicio <= '$ahora'
		AND multimedia.h_final >= '$ahora'
		
		UNION
		
		SELECT multimedia.tipo,
        multimedia.url,
        multimedia.duracion
        FROM multimedia
        WHERE multimedia.url <> ''
        AND multimedia.activo = 1
        AND DATE(multimedia.f_inicio) <= '$hoy' 
        AND DATE(multimedia.f_final) >= '$hoy'
		AND multimedia.h_inicio = ''";
    $resultado = $bd->ejecutar($consulta_sql);
    $datos = array();
    while ($fila = $bd->obtener_fila($resultado, 0)) {
        $datos[] = $fila;
    }
    return $datos;
}
