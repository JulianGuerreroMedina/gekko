<?php
include 'private/Core/UploadFile.php';

function ProcesaFormAgregarSustento()
{
    $resultado=''; $mensaje='';
    $array_bandera = ['error','error inesperado']; 
    $bandera = 1; 
    $id_multimedia = 0; 

    if (isset($_POST['id_multimedia']))
    {
        $id_multimedia = SanitizaID($_POST['id_multimedia']);
    }

    if (isset($_POST['descripcion']))
    {
        $descripcion = TxtParaBD($_POST['descripcion']);
        if (strlen($descripcion) > 200)
        {
            $descripcion = substr($descripcion, 0, 200);
        }

        if ($descripcion == '' )
        {   
            unset($array_bandera);
            $array_bandera = ['descripcion', ''];
            $bandera = 0;
        }
    }

    if ($bandera == 1)
    {       
        $carpeta_destino = "media/"; 
        $array = UploadFile('archivo', $carpeta_destino, 200);

        if (isset($array[0]))
        {
            $resultado = $array[0];
        }       
        
        if (isset($array[1]))
        {
            $mensaje =  $array[1];
        }       

        if ($resultado <> 'ok')
        {
            unset($array_bandera);
            $array_bandera = [$resultado, $mensaje];
        }
        else
        if ($resultado == 'ok')
        {
            $url = $array[1];
            $bd = new SQLite3(bd_dbname);
            $bdconsulta = "UPDATE multimedia SET
            url = '$url',
            descripcion = '$descripcion'
            WHERE id = '$id_multimedia'";
            $bd->exec($bdconsulta);
            $bd->close();
            unset($array_bandera);
            $array_bandera = [$resultado, $id_multimedia];
        }
    }
    return $array_bandera;
}

function FormAgregarSustento()
{
    $cadena = 'error';
    $id_multimedia = 0;
    if(isset($_POST['id_multimedia'])){ $id_multimedia = SanitizaID($_POST['id_multimedia']); }
    $bd = new SQLite3(bd_dbname);
    $bdconsulta = "SELECT multimedia.tipo
    FROM multimedia 
    WHERE multimedia.id = '$id_multimedia'
    LIMIT 0,1";
    $consulta = $bd -> query("$bdconsulta");
    while ($row = $consulta->fetchArray()) {
        $tipo = $row["tipo"];

        if ($tipo == '1')
        {
            $cadena_accept = 'image/*';
        }
        else    
        if ($tipo == '2')
        {
            $cadena_accept = 'video/*';
        }
    }

    $cadena = "<form action=\"\" method=\"POST\" id=\"form_subir\" name=\"form_subir\" accept-charset=\"UTF-8\">
        <ul>
            <li>
                <input id=\"archivo\" name=\"archivo\" type=\"file\" onchange=\"ValidaArchivoSubir('20000')\" accept=\"$cadena_accept\"/>
            </li>   
            <li>
                <label for=\"descripcion\">Descripci&oacute;n</label>
                <textarea id=\"descripcion\" name=\"descripcion\" rows=\"5\"></textarea>
            </li>
        </ul>
        <div class=\"botonera\">
        <input type=\"reset\" id=\"btnCancelar\" name=\"btnCancelar\" class=\"boton\" value=\"Cancelar\" onclick=\"msCerrar()\"/>
        <input type=\"submit\" id=\"btnEnviar\" name=\"btnEnviar\" class=\"boton\" value=\"Cargar\" onclick=\"ProcesaFormAgregarSustento('$id_multimedia')\"/>
    </div>
    </form>"; 
    return $cadena;
}
