<?php

function TipoMIME($tipo_archivo, $extension, $extenciones_validas = '')
{
    $cadena = false;
    $tipo_archivo = strtolower($tipo_archivo);
    $extension = strtolower($extension);
    $cadena = ValidaExtencionVsMime($tipo_archivo, $extension);
    if (($extenciones_validas <> '') AND ($cadena == true)) {
        $cadena = ValidaPorTipoExtencion($extension, $extenciones_validas);
    }
    return $cadena;
}

function ValidaPorTipoExtencion($extension, $extenciones_validas)
{
    /* Comprueba que $por_extension exista y ademas coincida con la extension que tiene el archivo cargado*/
    $cadena = false;
    $array = explode(',', trim(strtolower($extenciones_validas)));
    foreach ($array as $array):
        if (trim(strtolower($array)) == trim(strtolower($extension)))
        {
            $cadena = true;
        }
    endforeach;
    return $cadena;
}

function ValidaExtencionVsMime($tipo_archivo, $extension)
{
    $cadena = false;
    if (
        (($tipo_archivo == "video/mp4") and ($extension == "mp4")) or
        (($tipo_archivo == "video/webm") and ($extension == "webm")) or
        (($tipo_archivo == "image/jpeg") and ($extension == "jpg")) or
        (($tipo_archivo == "image/jpeg") and ($extension == "jpeg")) or
        (($tipo_archivo == "image/jpg") and ($extension == "jpg")) or
        (($tipo_archivo == "image/png") and ($extension == "png"))

    ) {
        $cadena = true;
    }
    return $cadena;
}
