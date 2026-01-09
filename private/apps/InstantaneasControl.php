<?php

include 'InstantaneasModelo.php';

function Instantaneas()
{
    $hoy = date('Y-m-d');
    $ahora = date('H:m');

    $resultados[] = [0,10,0];
    //$resultados = [];

    $consulta =  bdInstantaneas($hoy, $ahora);
    $cr = count($consulta);
    if ($cr >= 1)
    {
        foreach ($consulta as $consulta):
            $tipo = $consulta['tipo'];
            $duracion = $consulta['duracion'];
            $url = $consulta['url'];
            // Añadir los datos a los resultados
            $resultados[] = [$tipo, $duracion, $url];
        endforeach; 
    }
    return $resultados;
}
