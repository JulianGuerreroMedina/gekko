<?php
$funcion = '';

if ($_POST)
{
    if (isset($_POST["funcion"]))
    {
        $funcion = TxtParaBD($_POST["funcion"]);
    }

    if ($funcion == 'ProcesaFormAgregarSustento')
    {
        include 'private/apps/CargarArchivoMultimedia.php';
        print json_encode(ProcesaFormAgregarSustento());
    }

    if ($funcion == 'FormAgregarSustento')
    {
        include 'private/apps/CargarArchivoMultimedia.php';
        print FormAgregarSustento();
    }
}
