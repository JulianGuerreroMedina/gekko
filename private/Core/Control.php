<?php
session_start();


/**
 * Valida si un usuario esta logueado o 
 * si su sesion por inactividad ha terminado.
 * 
 * @param type $RetornoErrorPagina
 *  0 Redirige a la pagina de incio para loguearse de nuevo
 *  1 Interumpe una Ventana Modal o peticion Ajax
 *  2 Interumpe una pagina PopUP
 */
function ValidaUsuario($RetornoErrorPagina)
{
    $cadenasessid = ""; $ctivo_bd = "";  $UsuarioValido = true; 
    $hora_actual = date("Y-n-j H:i:s"); $entrada_bd = "1000-01-01 00:00:00";
    $userAgent = ""; $IPaddress = "";
    CompruebaMantenimiento();
    
    if (!(session_status()))
    {
        $UsuarioValido = false;
    }
    else
    if (session_status())
    {
        if (isset($_SESSION['CADENASESSID' . $GLOBALS['CADENASESSID']]))
        {
            $cadenasessid = $_SESSION['CADENASESSID' . $GLOBALS['CADENASESSID']];
        }
        
        if (isset($_SESSION['userAgent']))
        {
            $userAgent = $_SESSION['userAgent'];
        }
        
        if (isset($_SESSION['IPaddress']))
        {
            $IPaddress = $_SESSION['IPaddress'];
        }
        
        if (($userAgent <> $_SERVER['HTTP_USER_AGENT']) OR ($IPaddress <> $_SERVER['REMOTE_ADDR']))
        {
            $UsuarioValido = false;
        }
    
        if (isset($_SESSION['ID_usuario']))
        {
            $consulta=bdValidaUsuarioActivo($_SESSION['ID_usuario']);
            foreach ($consulta as $consulta): 
                $ctivo_bd = $consulta['activo']; 
                $entrada_bd = $consulta['entrada']; 
                $cadena_bd = $consulta['cadena']; 
            endforeach;
        }
        
        /* Compara difrerentes variables */
        if (($ctivo_bd == 0) OR 
           ($entrada_bd == "1000-01-01 00:00:00") OR 
           ($cadena_bd == "") OR 
           ($cadena_bd <> $cadenasessid)
        )
        {
            $UsuarioValido = false;
        }
        
        /* Compara si el tiempo transcurrido sin actividad contra el tiempo maximo permitido de incactividad */
        $tiempo_transcurrido = strtotime($hora_actual)-strtotime($entrada_bd); 
        if($tiempo_transcurrido >= $GLOBALS['TiempoInactivo'])
        {
            $UsuarioValido = false;
        }
    }

    /* reaccion si el usuario no esta con una session valida */
    if ($UsuarioValido == false) 
    {
        unset ($_SESSION['Permisos']);

        $origen=$GLOBALS['OrigenURL'];
        if (session_status())
        {
            session_unset();
            session_destroy();
        }

        if ($RetornoErrorPagina == 0)
        {
            /* 0 Redirige a la pagina de incio para loguearse de nuevo */
            header ("Location: ".$GLOBALS['URL_Post_Login']);
            exit();
        }
        else
        if ($RetornoErrorPagina == 1)
        {
            /* 1 Interumpe una Ventana Modal o peticion Ajax */
            exit('<img src="" onerror="AutoOff()">');
        }
        else
        if ($RetornoErrorPagina == 2)
        {    
            /* 2 Interumpe una pagina PopUP */
            Pagina404();
            exit();
        }
        
        /* Para todas las demas..... */
        header ("Location: ".$GLOBALS['OrigenURL'] . $GLOBALS['URL_Post_Login'] );
        exit();
    }
    else
    /* reaccion si el usuario es valido continua con su session */
    if ($UsuarioValido == true)
    {
        $_SESSION['UltimaEntrada'] = $hora_actual;
        bdActualizaHoraCadenaUsuario($_SESSION['ID_usuario'],$hora_actual,$_SESSION['CADENASESSID' . $GLOBALS['CADENASESSID'] ]); 
    }
}

function IdUsuario()
{
    if (isset($_SESSION['ID_usuario']))
    {
        return $_SESSION['ID_usuario']; 
    }
}

function CompruebaMantenimiento()
{
    if (isset($_SESSION['ID_usuario']))
    {
        $consulta = bdListaEnMantenimiento();
        $cantidadresultados = count($consulta);
        if ($cantidadresultados >= 1)
        {
            $continuarSesionEnMantenimiento = false;
            $IdUsuarioaBuscar = $_SESSION['ID_usuario'];
            foreach ($consulta as $consulta):
                $id_usuario = $consulta['id_usuario'];
                if ($IdUsuarioaBuscar == $id_usuario)
                {
                    $continuarSesionEnMantenimiento = true;
                }
            endforeach;
            if ($continuarSesionEnMantenimiento == false)
            {
                header ("Location: " . $GLOBALS['OrigenURL'] . ""); 
                exit();
            }
        }
    }
}

function NombreUsuarioPorId($id_usuario)
{
    $GetUsuarioObjeto = bdGetUsuarioObjeto($id_usuario); 
    foreach ($GetUsuarioObjeto as $GetUsuarioObjeto): 
        $nombre = $GetUsuarioObjeto['nombre']; 
    endforeach; 
    return $nombre; 
}

function Salir()
{
    bdActualizaHoraCadenaUsuario($_SESSION['ID_usuario'],"1000-01-01 00:00:00",""); 
    //setcookie('UltimaURL', " ", array('expires' =>time() + 96000, 'path' => '/',  'httponly' => 'true',  'samesite' => 'Strict'));
    //setcookie("Bienvenida", "", array('expires' =>time() + 1, 'path' => '/',  'httponly' => 'true',  'samesite' => 'Strict'));
    if (session_status())
    {
        session_unset();
        session_destroy();
    }
    header ("Location: " . $GLOBALS['URL_Login']); 
}

function AutoOff()
{
    $login_activo = 1;  $UltimaEntrada = 0;
    if (isset($_SESSION['UltimaEntrada'])){$UltimaEntrada = strtotime($_SESSION['UltimaEntrada']);}
    $tiempo_actual = strtotime(date("Y-n-j H:i:s"));
    $tiempo_transcurrido = $tiempo_actual-$UltimaEntrada;
    if ($tiempo_transcurrido >= $GLOBALS['TiempoInactivo'])
    {
        if (session_status())
        {
            session_unset();
            session_destroy();
        }
        $login_activo = 0;
    }
    return $login_activo;
}

/**
 * Genera un Status: 404 Not Found.<br>
 * Y carga la pagina generica de 404, <br>
 * deteniendo todo el flujo de codigo con la funcion exit()
 */
function Pagina404()
{
    $includes = '';
    header("HTTP/1.0 404 Not Found");
    header("Status: 404 Not Found");
    LogPagina404();
    $titulo = "P&aacute;gina no encontrada &oacute; se ha detectado un error...";
    $layout = file_get_contents('private/vistas/error404.html');
    $pagina = IntegraLayout($layout,'','',$titulo,'','','','');
    foreach (get_included_files() as $nombre_archivo)
    {
        $includes .= "\n$nombre_archivo\n";
    }
    
    error_log("\n". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    echo $pagina;
    exit();
}