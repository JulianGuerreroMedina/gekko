<?php

include 'AdminModelo.php';

function ListaMultimedia()
{
    $array_respuesta = ['respuesta' => 'error', 'msg_error' => 'Ocurrió un error inesperado'];
    $cadena = '';
    $i = 1;
    $horario = '';
    $url = '';

        
    $consulta = bdListaMultimedia();
    $cr = count($consulta);
    if ($cr <= 0)
    {
        $array_respuesta = ['respuesta' => 'sin_datos', 'msg_error' => 'No hay recursos multimedia cargados'];
        return $array_respuesta;
    }
    if ($cr >= 1)
    {
        foreach ($consulta as $consulta):
            $id_multimedia = $consulta['id'];
            $tipo = $consulta['tipo'];
            $descripcion = $consulta["descripcion"];
            $url = $consulta["url"];
            $f_inicio = ddmmaaaa($consulta["f_inicio"]);
            $f_final = ddmmaaaa($consulta["f_final"]);
            $h_inicio = $consulta["h_inicio"];
            $h_final = $consulta["h_final"];
            $horario = "$h_inicio hrs - $h_final hrs";
            $duracion = $consulta["duracion"] . 's';
            $activo = $consulta["activo"];

            if ($tipo == 1) {
                $nombretipo = "Imagen";
            } else              
            if ($tipo == 2) {
                $nombretipo = "Video";
                $duracion = '';
            }

            if ($descripcion == '') {
                $descripcion = "Sin Descripci&oacute;n";
            }
            
            if ($url <> '') {
                $cadena_url = $url;
            } else
            if ($url == '') {
                $cadena_url = "<span id_multimedia=\"$id_multimedia\" class=\"subir_multimedia file-upload puntero\"></span>";
            }

            if ($h_inicio == '' && $h_final == '') {
                $horario = "---";
            }

            if ($h_inicio == '00:00:00' || $h_final == '00:00:00') {
                $horario = "---";
            }

            if ($activo == 1) {
                $cadena_set = "btn-toggle-on";
            } else {
                $cadena_set = "btn-toggle-off";
            }

            $cadena .= "<tr id=\"tr_$id_multimedia\">
                <td>$i</td>
                <td>$nombretipo</td>
                <td id=\"id_descripcion_$id_multimedia\">$descripcion</td>
                <td>$duracion</td>
                <td>$f_inicio</td>
                <td>$f_final</td>
                <td>$horario</td>
                <td>
                    <span id_multimedia=\"$id_multimedia\" class=\"btn_set $cadena_set puntero\"></span>   
                    <span id_multimedia=\"$id_multimedia\" class=\"btn_borrar trash puntero\"></span> 
                    <span id_multimedia=\"$id_multimedia\" class=\"btn_editar btn-edit puntero\"></span>  
                </td>
            </tr>";
            $i = $i + 1;
            endforeach;
            $array_respuesta = ['respuesta' => 'ok', 'html' => $cadena];
        }
    return $array_respuesta;
}

function AdminControl()
{
    $cadena = "<h1 class=\"titulo2\">Recursos Multimedia</h1>
    <table id=\"publicaciones\" class=\"table bordered\">
        <thead>
            <tr>
                <th>#</th>
                <th>Tipo</th>
                <th>Descripcion</th>
                <th>Duraci&oacute;n</th>
                <th>Fecha Inicio</th>
                <th>Fecha Final</th>
                <th>Horario</th>
                <th><span id=\"btn_add_multimedia\" class=\"btn-plus puntero\"></span></th>
            </tr>
        </thead>
        <tbody id=\"multimedia\"></tbody>
    </table>";
    return $cadena;
}
