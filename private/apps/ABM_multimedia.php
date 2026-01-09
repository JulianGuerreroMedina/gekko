<?php
include 'AdminModelo.php';

function ProcesaFrmBorrarMultimedia()
{
    $array_respuesta = ['respuesta' => 'error', 'msg_error' => 'Ocurrió un error inesperado'];
    $id_multimedia = 0;

    if (isset($_POST["id_multimedia"]))
    {
        $id_multimedia = SanitizaID($_POST["id_multimedia"]);
    }

    if ($id_multimedia >= 1)
    {
        $consulta = bdMultimediaPorId($id_multimedia);
        $cr = count($consulta);
        if ($cr == 0)
        {
            return 'error';
        } else
        if ($cr == 1)
        {
            foreach ($consulta as $row):
                $url     = $row["url"];
            endforeach;
        
            //bdBorrarMultimedia($id_multimedia);
    
            if ($url <> '')
            {
                unlink("media/$url");
            }
            $array_respuesta = ['respuesta' => 'ok', 'msg_ok' => 'Recurso multimedia borrado con exito'];
        }
    }
    return $array_respuesta;
}

function validar_horario()
{
    $bandera = 1;
    $h_inicio = 0;
    $h_final = 0;

    if (isset($_POST['h_inicio'])){
        $h_inicio = ($_POST['h_inicio']);

        if ($h_inicio == '')
        {
            $h_inicio = 0;
        }
        else
        if ($h_inicio <> '')
        {
            $h_inicio =  ltrim($h_inicio, '0');
            $h_inicio =  intval(str_replace(':', '', $h_inicio));
        }
    }

    if (isset($_POST['h_final'])){
        $h_final = ($_POST['h_final']);
        if ($h_final == '')
        {
            $h_final = 0;
        }
        else
        if ($h_final <> '')
        {
            $h_final =  ltrim($h_final, '0');
            $h_final =  intval(str_replace(':', '', $h_final));
        }
    }

    if ($h_inicio > $h_final)
    {
        $bandera = 0;
    }

    return $bandera;    
}

function frmSetear()
{
    $cadena = 'error';
    $id_multimedia = 0;

    if (isset($_POST["id_multimedia"]))
    {
        $id_multimedia = SanitizaID($_POST["id_multimedia"]);
    }

    if ($id_multimedia >= 1)
    {
        $consulta = bdMultimediaPorId($id_multimedia);
        $cr = count($consulta);
        if ($cr == 0)
        {
            return 'error';
        } else
        if ($cr == 1)
        {
            foreach ($consulta as $row):
                $activo = $row["activo"];
            endforeach;

            if ($activo == 1)
            {
                bdSetMultimedia($id_multimedia, 0);
                $cadena = '0';
            } else {
                bdSetMultimedia($id_multimedia, 1);
                $cadena = '1';
            }
        }
    }
    return $cadena;
}

function verMiniatura($tipo, $url)
{
    $cadena = '';
    if (($tipo <> '') AND ($url <> ''))
    {
        if ($tipo == 1)
        {
            $cadena = "<div class=\"abm_thumb\">
                <img src=\"".$GLOBALS['OrigenURL']."/media/$url\" alt=\"Imagen\"/>
            </div>";
        }
        else
        if ($tipo == 2)
        {
            $cadena = "<div class=\"abm_thumb\">
                <video controls >
                    <source src=\"".$GLOBALS['OrigenURL']."/media/$url\" type=\"video/mp4\">
                    Your browser does not support the video tag.
                </video>
            </div>";
        }
    }
    return $cadena;
}
function ProcesaFrmAbmMultimedia()
{
    $bandera = 1; 
    $array_bandera = ['error'];   
    $id_multimedia = 0;
    $duracion = 20;
    $h_inicio = '00:00';
    $h_final = '00:00';

    if (isset($_POST['id_multimedia'])){
        $id_multimedia = SanitizaID($_POST['id_multimedia']);
    }

    if (isset($_POST['tipo'])){
        $tipo = $_POST['tipo'];
        if ($tipo == 'nulo') 
        {
            array_push($array_bandera,'tipo'); $bandera = 0;
        }
    }

    if (isset($_POST['descripcion'])){
        $descripcion = TxtParaBD($_POST['descripcion']);
        if ($descripcion == '') 
        {
            array_push($array_bandera,'descripcion');
        }
    }

    if (isset($_POST['duracion'])){
        $duracion = $_POST['duracion'];
    }
    
    if (isset($_POST['f_inicio'])){
        $f_inicio = ($_POST['f_inicio']);
        if ($f_inicio == '') 
        {
            array_push($array_bandera,'f_inicio'); $bandera = 0;
        }
    }

    if (isset($_POST['f_final'])){
        $f_final = ($_POST['f_final']);
        if ($f_final == '') 
        {
            array_push($array_bandera,'f_final'); $bandera = 0;
        }
    }

    if (isset($_POST['h_inicio'])){
        $h_inicio = $_POST['h_inicio'];
    }

    if (isset($_POST['h_final'])){
        $h_final = $_POST['h_final'];
    }

    $bandera_horario = validar_horario();
    if ($bandera_horario == 0)
    {
        array_push($array_bandera,'btn_horario'); 
        $h_inicio = '00:00';
        $h_final = '00:00';
    }
 
    if ($bandera == 1)
    {
        $bd = new SQLite3($_ENV['BD_DBNAME']);
        if ($id_multimedia >= 1)
        {
            bdModificarMultimedia($id_multimedia, $tipo, $descripcion, $duracion, $f_inicio, $f_final, $h_inicio, $h_final);
            $array_bandera[0] = 'modificado';
        }
        else
        if ($id_multimedia == 0)
        {
            $id_multimedia = bdAgregarMultimedia($tipo, $descripcion, $duracion, $f_inicio, $f_final, $h_inicio, $h_final);
            $array_bandera[0] = 'agregado';
        }
    }
    return $array_bandera;
}

function FrmAbmMultimedia()
{
    $cadena = 'error';
    $nombre_boton = 'Agregar';
    $tipo = 'nulo';
    $duracion = '20';
    $option_multimedia = '';
    $descripcion = '';
    $url = '';
    $f_inicio = '';
    $f_final = '';
    $h_inicio = '';
    $h_final = '';
    $option_nulo = "<option value=\"nulo\">Seleccionar</option>
        <option value=\"1\">Imagen</option>
        <option value=\"2\">Video</option>";

    $cadena_duracion = "<li>
        <label for =\"duracion\">Duraci&oacute;n</label>
        <select id=\"duracion\" name=\"duracion\" >
            <option value=\"10\"".MarcaSelected($duracion, '10').">Corto</option>
            <option value=\"20\"".MarcaSelected($duracion, '20').">Medio</option>
            <option value=\"30\"".MarcaSelected($duracion, '30').">Largo</option>
        </select>
    </li>";

    if (isset($_POST["id_multimedia"]))
    {
        $id_multimedia = SanitizaID($_POST["id_multimedia"]);
    }

    if ($id_multimedia >= 1)
    {
        
        $consulta = bdMultimediaPorId($id_multimedia);
        $cr = count($consulta);
        if ($cr == 0)       
        {
            return 'error';
        } else 
        if ($cr == 1)       

        foreach ($consulta as $row):
            $tipo = $row["tipo"];
            $descripcion = $row["descripcion"];
            $url = $row["url"];
            $duracion = $row["duracion"];
            $f_inicio = $row["f_inicio"];
            $f_final = $row["f_final"];
            $h_inicio = $row["h_inicio"];
            if ($h_inicio == '00:00'){$h_inicio = '';}
            $h_final = $row["h_final"];
            if ($h_final == '00:00'){$h_final = '';}
        
        $nombre_boton = 'Modificar';
        $option_nulo = '';

        if ($tipo == 1)
        {
            $option_multimedia = '<option value="1">Imagen</option>';
        }   
        else
        if ($tipo == 2)
        {
            $option_multimedia = '<option value="2">Video</option>';
            $cadena_duracion = '';
        }   
        endforeach;
    }

    $cadena = "<form id=\"FrmAbmMultimedia\">
        <input type=\"hidden\" name=\"id_multimedia\" value=\"$id_multimedia\"/>
        <ul>
            ".verMiniatura($tipo, $url)."
            <li>
                <label for =\"tipo\">Tipo</label>
                <select id=\"tipo\" name=\"tipo\" >
                    $option_nulo
                    $option_multimedia
                </select>
            </li>
            <li>
                <label for=\"descripcion\">Descripci&oacute;n</label>
                <input type=\"text\" id=\"descripcion\" name=\"descripcion\" value=\"$descripcion\"/>
            </li>
            $cadena_duracion
            <li>
                <label for=\"f_inicio\">Inicia</label>
                <input class=\"fecha_input\" type=\"date\" id=\"f_inicio\" name=\"f_inicio\" value=\"$f_inicio\"/>
            </li>
           <li>
                <label for=\"f_final\">Termina</label>
                <input class=\"fecha_input\" type=\"date\" id=\"f_final\" name=\"f_final\" value=\"$f_final\"/>
            </li>
            <fieldset>
            <legend>Horrario de Reproducci&oacute;n:</legend>
                <span>Desde: </span>
                <input class=\"hora_input\" type=\"time\" id=\"h_inicio\" name=\"h_inicio\" value=\"$h_inicio\"/>
                <span> Hasta: </span>                
                <input class=\"hora_input\" type=\"time\" id=\"h_final\" name=\"h_final\" value=\"$h_final\"/>
                <span id=\"btn_horario\" onclick=\"limpia_horario()\" class=\"calendar-cancel puntero\"></span>
            </fieldset>
        </ul>
        <div class=\"botonera\">
            <input type=\"reset\" id=\"btnCancelar\" name=\"btnCancelar\" class=\"boton\" value=\"Cancelar\" onclick=\"msCerrar()\"/>
            <input type=\"submit\" id=\"btnEnviar\" name=\"btnEnviar\" class=\"boton\" value=\"$nombre_boton\" onclick=\"ProcesaFrmAbmMultimedia(); return false;\"/>
        </div>
    </form>";
    return $cadena;
}