<?php


function ListaMultimedia()
{
    $cadena = 'error';
    $tr = '';
    $i = 1;
    $horario = '';
    $url = '';

        $bd = new SQLite3($_ENV['BD_DBNAME']);
        $bdconsulta = "SELECT multimedia.id,
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
        ORDER BY multimedia.activo DESC, multimedia.id DESC;";
        $consulta = $bd->query("$bdconsulta");
        while ($row = $consulta->fetchArray()) {
            $id_multimedia = $row["id"];
            $tipo = $row["tipo"];
            if ($tipo == 1) {
                $nombretipo = "Imagen";
            } else
            if ($tipo == 2) {
                $nombretipo = "Video";
            }
            $descripcion = $row["descripcion"];
            if ($descripcion == '') {
                $descripcion = "Sin Descripci&oacute;n";
            }
            
            $url = $row["url"];
            if ($url <> '')
            {
                $cadena_url = $url;
            }
            else
            if ($url == '')
            {
                $cadena_url = "<span id_multimedia=\"$id_multimedia\" class=\"subir_multimedia file-upload puntero\"></span>";
            }

            $duracion = $row["duracion"] . 's';

            if ($tipo == 2)
            {
                $duracion = '';
            }
            $f_inicio = ddmmaaaa($row["f_inicio"]);
            $f_final = ddmmaaaa($row["f_final"]);


            $h_inicio = $row["h_inicio"];
            $h_final = $row["h_final"];
            $horario = "$h_inicio hrs - $h_final hrs";

            if ($h_inicio == '' && $h_final == '') {
                $horario = "---";
            }

            if ($h_inicio == '00:00' || $h_final == '00:00') {
                $horario = "---";
            }

            $activo = $row["activo"];
            if ($activo == 1) {
                $cadena_set = "btn-toggle-on";
            } else {
                $cadena_set = "btn-toggle-off";
            }

            $tr .= "<tr id=\"tr_$id_multimedia\">
                <td>$i</td>
                <td>$nombretipo</td>
                <td>$descripcion</td>
                <td>$cadena_url</td> 
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
        }
    
   $cadena = "<h1 class=\"titulo2\">Recursos Multimedia</h1>
    <table id=\"publicaciones\" class=\"table bordered\">
        <thead>
            <tr>
                <th>#</th>
                <th>Tipo</th>
                <th>Descripcion</th>
                <th>URL</th>
                <th>Duraci&oacute;n</th>
                <th>Fecha Inicio</th>
                <th>Fecha Final</th>
                <th>Horario</th>
                <th><span id=\"btn_add_multimedia\" class=\"btn-plus puntero\"></span></th>
            </tr>
        </thead>
        <tbody>$tr</tbody>
    </table>";
    return $cadena;
}

function AdminControl()
{
    include 'private/apps/valida_bd_sqlite.php';
    ValidaBD();
}
