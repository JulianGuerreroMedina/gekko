<?php
$funcion = '';

if ($_POST) {
    if (isset($_POST["funcion"])) {
        $funcion = TxtParaBD($_POST["funcion"]);
    }


    if ($funcion == 'ProcesaFrm_borrar_multimedia') {
        include 'private/apps/ABM_multimedia.php';
        print ProcesaFrm_borrar_multimedia();
    }

    if ($funcion == 'Frm_borrar_multimedia') {
        include 'private/apps/ABM_multimedia.php';
        print Frm_borrar_multimedia();
    }

    if ($funcion == 'frmSetear') {
        include 'private/apps/ABM_multimedia.php';
        print frmSetear();
    }

    if ($funcion == 'ListaMultimedia') {
        include 'private/apps/adminControl.php';
        print ListaMultimedia();
    }

    if ($funcion == 'ProcesaFrm_abm_multimedia') {
        include 'private/apps/ABM_multimedia.php';
        print json_encode(ProcesaFrm_abm_multimedia());
    }

    if ($funcion == 'Frm_abm_multimedia') {
        include 'private/apps/ABM_multimedia.php';
        print Frm_abm_multimedia();
    }
}
