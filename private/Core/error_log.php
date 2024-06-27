<?php
/**
 * Un simple archivo que configura el log, muestra 
 * los errores y crea un nuevo archivo cada día
 */

if ($GLOBALS['desarrollo'] ==1)
{
    ini_set('display_errors',1);
    error_reporting(E_ALL);
    
    # El directorio o carpeta en donde se van a crear los logs
    # da algo como C:\xampp\esta_carpeta\logs
    define("RUTA_LOGS", $_SERVER["DOCUMENT_ROOT"]. "/logs");
    # Crear carpeta si no existe
    if (!file_exists(RUTA_LOGS)) {
        mkdir(RUTA_LOGS);
    }
    # Poner fecha y hora de México, esto es por si el servidor tiene
    # otra zona horaria
    date_default_timezone_set("America/Mexico_City");
    # Configuramos el ini para que...
    # No muestre errores
    ini_set('display_errors', 1);
    # Los ponga en un archivo
    ini_set("log_errors", 1);
    # Y le indicamos en dónde los va a poner, sería en algo como:
    # RUTA_LOGS/2019-02-07.log
    # Así cada día tenemos un archivo de log distinto
    ini_set("error_log", RUTA_LOGS . "/" . date("Y-m-d") . ".log");
}