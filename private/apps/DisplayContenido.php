<?php

function Instantaneas()
{

    $hoy = date('Y-m-d');
    $ahora = date('H:m');

    $resultados[] = ["ImgLogo",10,0];
    if (file_exists(bd_dbname))
    {
        $bd = new SQLite3(bd_dbname);
        $bdconsulta = "SELECT multimedia.tipo,
        multimedia.url,
        multimedia.duracion
        FROM multimedia
        WHERE multimedia.url <> ''
        AND multimedia.activo == '1' 
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
        AND multimedia.activo == '1' 
        AND DATE(multimedia.f_inicio) <= '$hoy' 
        AND DATE(multimedia.f_final) >= '$hoy'
		AND multimedia.h_inicio == ''";
        $consulta = $bd -> query("$bdconsulta");
        while ($row = $consulta -> fetchArray())
        {
            $tipo = $row["tipo"];
            $duracion = $row["duracion"];
            $url = $row["url"];
            // Añadir los datos a los resultados
            $resultados[] = [$tipo, $duracion, $url];
        }
    }
    return $resultados;
}
