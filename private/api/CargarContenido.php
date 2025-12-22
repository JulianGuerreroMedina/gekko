<?php

if ($_POST) {
    if (isset($_POST["funcion"]) == 'Instantaneas') {
        include 'private/apps/DisplayContenido.php';
        print json_encode(Instantaneas());
    }
}
