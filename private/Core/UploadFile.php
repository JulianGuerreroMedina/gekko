<?php
include 'TiposMIME.php';

/**
 * Se encarga de validar el archivo que se va a cargar
 * y almacenar el archivo en el servidor
 * @param String $input_file el id del input tipo file del formulario
 * @param String $carpeta_destino La ubicacion final para cargar el archivo en el servidor
 * @param Integer $max_file_MB el tamaño maximo del archivo dado en MB
 * @param Text $extenciones_validas Solo permite subir las extensiones indicadas y separadas por coma
 * @return Array Retorna en array de dos elementos:<br>
 * array[0]: contine el resultado de la carga, <br>
 * array[1]: contiene un mensaje del error, o si fue exitosa la carga, retorna el nombre del arhchivo cargado
 */
function UploadFile($input_file, $carpeta_destino, $max_file_MB, $extenciones_validas = '')
{
    $array_bandera = ['error', 'error inesperado'];
    $bandera = 1;
    if ($_FILES[$input_file])
    {
        $nombre_archivo = $_FILES[$input_file]['name'];
        $tamanio_archivo = $_FILES[$input_file]['size'];
        $input_tipo = strtolower ( trim ($_FILES[$input_file]['type']));
        $input_extension = strtolower ( pathinfo ( $nombre_archivo, PATHINFO_EXTENSION ) );
               
        if (!(TipoMIME($input_tipo, $input_extension, $extenciones_validas)))
        {
            //Valida el tipo de archivo
            unset($array_bandera);
            $array_bandera = ['error_mime', $input_tipo];
            $bandera = 0;
        }

        if (($max_file_MB * 1048576) < $tamanio_archivo )
        {
            //Valida el tamaño del archivo cargado
            unset($array_bandera);
            $array_bandera = ['error_size', 'error_size'];
            $bandera = 0;
        }
    
        if ($bandera == 1)
        {
            $nombrearch = substr( bin2hex( random_bytes(15)) . '.' . $input_extension, -25);
            $carpeta_destino = rtrim($carpeta_destino, '/');
            if(!is_dir ( $carpeta_destino) ){ @mkdir( $carpeta_destino, 0777, true); }
            $destino_arch = $carpeta_destino . '/'. $nombrearch;
            if (move_uploaded_file ($_FILES [$input_file]['tmp_name'], $destino_arch))
            {
                if (file_exists($destino_arch))
                {
                    unset($array_bandera);
                    $array_bandera = ['ok', $nombrearch];
                }
            }
        }
    }
    return $array_bandera;
}