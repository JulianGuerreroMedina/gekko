<?php

/**
  * Valida que el parametro de la URL sea Unicamente Numerico o NULL
  * @param type $parametro integer Posicion del Array que va a analizar.
  * @return Error404
 */
function ValidaIdABM($parametro)
{
    if (isset($_GET['url']))
    {
        $url=TxtParaBD(trim($_GET['url']));
        $url=rtrim($url, '/');    
        $url= filter_var($url,FILTER_SANITIZE_URL);
        $url=explode('/',$url);
        if (isset($url["$parametro"]))
        {
            if (SanitizaID($url["$parametro"])==0){Pagina404();}
        }
    }
}

/**
 * Formatea un numero con el prefijo $ y le agrega dos decimales de sufijo.
 * @param type $numero integerarray key 
 * @return String
 */
function FormatoMoneda($numero)
{
    return "$".number_format($numero, 2, ".", ","); 
}

/**
 * Genera una caceda aleatoria segun la longitud indicada
 * @param Integer Numero o Longitud de caracteres de la cadena a generar 
 * @return String Retorna una cadena aleatoria
 */
function GeneraCadenaAlfanumerica($longitud)
{
    $caracteres = "0123456789abcdefghijklmnpqrstuvwxyz";
    $cadena = substr(str_shuffle($caracteres), 0, $longitud);
    return $cadena;
}

/**
 * Procesa los campos de texto de una consulta sql
 * 
 * Representa correctamente el texto con sus entidades HTML
 * Si hay saltos de linea los sustituye por un espacio en blanco
 * @param type $cadena El string para procesar
 * @return String
 */
function BDParaTxt($cadena)
{
    if ($cadena <> '')
    {
        $cadena = htmlentities($cadena, ENT_QUOTES); 
        /* Sustituye el salto de linea por un espacio */
        $cadena = preg_replace('/\s\s+/', ' ', $cadena);
    }
    return $cadena;
}

/**
 * Procesa los campos de texto de una consulta sql con saltos de linea.
 * 
 * Representa correctamente el texto con sus entidades HTML.
 * Si hay saltos de linea los reemplaza por la etiqueta br.
 * @param type $cadena El string para procesar
 * @return String
 */
function BDParaTxtLn($cadena)
{
    if (!empty($cadena))
    {
        $cadena = nl2br(htmlentities($cadena, ENT_QUOTES)); 
    }
    return $cadena;
}

/**
 * Procesa los campos de texto para insertar a una consulta sql.
 * 
 * Procesa las letras permitidas acentuadas a UTF-8.
 * Elimina caracteres no permitidos para evitar inteccion SQL.
 * Si hay saltos de linea los conserva y los guarda en la BD.
 * @param type $cadena El string para procesar
 * @return String
 */
function TxtParaBD($cadena)
{
    if (!(empty($cadena)))
    {
        $cadena=trim($cadena);
        $buscar=array('Á','É','Í','Ó','Ú','á','é','í','ó','ú','ñ','Ñ','<','>','(',')','@','#','%','$','_','-','/');
        $reemplazar=array('&Aacute;','&Eacute;','&Iacute;','&Oacute;','&Uacute;','&aacute;','&eacute;','&iacute;','&oacute;','&uacute;','&ntilde;','&Ntilde;','&lt;','&gt;','&lpar;','&rpar;','&commat;','&num;','&percnt;','&dollar;','&lowbar;','&hyphen;','&sol;');
        $cadena = str_replace( $buscar,$reemplazar,$cadena);
        $cadena = preg_replace("/[^a-zA-Z0-9&:.,; \s+ ]/", '', $cadena); /*Salto de linea: \s+  */
        $cadena = str_replace( $reemplazar, $buscar, $cadena);
        $cadena = htmlentities($cadena, ENT_QUOTES,"utf-8"); 
        $cadena = html_entity_decode ($cadena, ENT_QUOTES); 
        $cadena = strip_tags($cadena);
        $cadena = addslashes ($cadena) ;
    }
    return $cadena;
}

/**
 * Formatea un string con el formato 1000-01-01 00:00:00
  * 
 * @param type $fecha_hora DateTime 1000-01-01 00:00:00
 * @return String Retorna la hora en formato a.m / p.m
 */
function FormatoHora($fecha_hora)
{
    $meridiano=" a.m.";
    $hora=substr($fecha_hora, 11, 2);
    if($hora>=12){$meridiano=" p.m.";}  
    if($hora>=13){$hora=$hora-12;}
    $min=substr($fecha_hora, 14, 2); 
    $horafinal=sprintf("%02s", $hora).":$min $meridiano"; 
    return $horafinal;
}

/**
 * Formatea una fecha aaaa/mm/dd para: 11 de Diciembre de 1997.
 * 
 * @param Date $fecha en formato aaaa/mm/dd
 * @return String Retorna en formato "11 de Diciembre de 1997".
 */
function FormatoFechaEspaniol($fecha)
{
    $fecha=aaaammdd($fecha);
    $dia=date("j",strtotime("$fecha")); 
    $mes=date("n",strtotime("$fecha"))-1; 
    $mes_array=array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    $nombre_mes= $mes_array[$mes];
    $anio=date("Y",strtotime("$fecha")); 
    return ($dia." de ".$nombre_mes." de ".$anio); 
}

/**
 * Obtiene el nombre del dia  en español
 * 
 * @param Date $fecha en formato aaaa/mm/dd
 * @return String Retorna "Domingo","Lunes","Martes"...
 */
function FormatoDiaEspaniol($fecha)
{
    $dia=date("w",strtotime("$fecha")); 
    $dia_array=array("Domingo","Lunes","Martes","Mi&eacute;rcoles","Jueves","Viernes","Sabado");
    return $dia_array[$dia];
}

/**
 * Obtiene el nombre del mes en español
 * 
 * @param Date $fecha en formato aaaa/mm/dd
 * @return String Retorna "Enero","Febrero","Marzo"...
 */
function FormatoMesEspaniol($fecha)
{
    $mes=date("n",strtotime("$fecha"))-1; 
    $mes_array=array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    return $mes_array[$mes];
}

/**
 * Compara dos fechas y calcula los dias de diferencia <br>
 * entre la primera y la segunda fecha
 * 
 * @param type $fi Date 
 * @param type $ff Date 
 * @return Integer Numero de dias de Diferencia
 */
function DiasTranscurridosEntreDosFechas($fi, $ff)
{
    $cadena='-1'; 
    $fechainicio=aaaammdd($fi);
    $fechafinal=aaaammdd($ff);
    if (($fechainicio<>'1000-01-01') AND ($fechafinal<>'1000-01-01'))
    {    
        if ($fechafinal>=$fechainicio)
        {
            $cadena = abs(ceil((strtotime($fechafinal) - strtotime($fechainicio)) / (60 * 60 * 24)));
        }
    }
    return $cadena;
}

/**
 * Formatea una fecha con salida dd/mm/aaaaa
 * 
 * @param type $fecha Admite los formatos aaaa/mm/dd o dd/mm/aaaa
 * @return Date Retorna Fecha Valida con formato dd/mm/aaaa,<br>
 * si no es una fecha valida retorna un campo vacio.
 */
function ddmmaaaa($fecha)
{
    $cadena = '';
    if($fecha <> '')
    {
    $cadena_0 = ''; $cadena_1 = ''; $cadena_2 = ''; 
    $fecha = substr($fecha, 0, 10); 
    $fecha = preg_replace('/[^0-9]/','-',trim($fecha));
    $explode = explode('-',$fecha);
    if (count($explode) == 3) 
    {
            if (isset($explode[0])){$cadena_0 = $explode[0];}
            if (isset($explode[1])){$cadena_1 = $explode[1];}
            if (isset($explode[2])){$cadena_2 = $explode[2];}

            if ((strlen($cadena_0)==4) AND (strlen($cadena_1)==2) AND (strlen($cadena_2)==2))
            {
                if (checkdate($cadena_1, $cadena_2, $cadena_0))
                {
                    $cadena="$cadena_2/$cadena_1/$cadena_0";
                }
            }
            else
            if ((strlen($cadena_0)==2) AND (strlen($cadena_1)==2) AND (strlen($cadena_2)==4))
            {
                if (checkdate($cadena_1, $cadena_0, $cadena_2))
                {
                    $cadena="$cadena_0/$cadena_1/$cadena_2";
                }
            }
        }
    }
    return $cadena;
}

/**
 * Valida una fecha asegurando que la fecha existe y por tanto es válida.
 * checkdate(int $month, int $day, int $year): bool
 * 
 * @param type $fecha Admite formatos aaaa/mm/dd
 * @return bool Retorna True / False<br>
 * si no es una fecha valida retorna un campo vacio.
 */
function ValidaFechaExistente($fecha)
{
    $cadena= false;
    $fecha = preg_replace('/[^0-9]/','-',trim($fecha));
    $explode = explode('-', $fecha);
    if(count($explode) == 3 && checkdate($explode[1], $explode[2], $explode[0])){
	$cadena= true;
    }
    return $cadena;
}

/**
 * Comprueba que la fecha con checkdate y formatea la salida aaaa/mm/dd
 * 
 * @param type $fecha Admite los formatos aaaa/mm/dd o dd/mm/aaaa
 * @return Date Retorna Fecha Valida o 1000-01-01 en su defecto
 */
function aaaammdd($fecha)
{
    $cadena="1000-01-01";
    if($fecha<>'')
    {        
        $cadena_0=""; $cadena_1=""; $cadena_2=""; 
        $fecha = preg_replace('/[^0-9]/','-',trim($fecha));
        $explode=explode('-',$fecha);
        if (count($explode)==3) 
        {
            if (isset($explode[0])){$cadena_0 = $explode[0];}
            if (isset($explode[1])){$cadena_1 = $explode[1];}
            if (isset($explode[2])){$cadena_2 = $explode[2];}

            if ((strlen($cadena_0)==4) AND (strlen($cadena_1)==2) AND (strlen($cadena_2)==2))
            {
                if (checkdate($cadena_1, $cadena_2, $cadena_0))
                {
                    $cadena="$cadena_0-$cadena_1-$cadena_2";
                }
            }
            else
            if ((strlen($cadena_0)==2) AND (strlen($cadena_1)==2) AND (strlen($cadena_2)==4))
            {
                if (checkdate($cadena_1, $cadena_0, $cadena_2))
                {
                    $cadena="$cadena_2-$cadena_1-$cadena_0";
                }
            }
        }
    }
    return $cadena;
}

/**
 * Limpia todos los valores de la cadena que no sean valores numericos.<br>
 * Solo perime simbolos "." <br>
 * Si no es un numero valido, retorna un valor vacio.
 * 
 * @param type $numero Number
 * @return Number Si es Numero valido regresa el valor
 */
function SanitizaNumero($numero)
{
    $numero = trim($numero);
    // esta funcion actualiza utf8_encode que está obsoleto
    $encoding = mb_detect_encoding( $numero, "auto" );
    $numero = mb_convert_encoding( $numero, "UTF-8", $encoding);
    $numero = preg_replace("/[^0-9.]/", '', $numero);
    if (!(is_numeric($numero))){$numero='';} 
    return $numero; 
}

/**
 * Retorna la URL desde donde se ejecuta el script.<br>
 * Agrega el protocolo "http", "https"...  tomado de la global $GLOBALS['OrigenURL'] <br>
 * y obtiene la uri actual del script con $_SERVER['REQUEST_URI']; 
 * 
 * @return string Retorna uri actual del script
 */
function UrlActual()
{
    return $GLOBALS['OrigenURL'].$_SERVER['REQUEST_URI']; 
}

/**
 * Genera un String HTML con un Mensaje de error
 * @param type $mensaje Titulo del mensaje
 * @return String Retorna un span con los estilos armados
 */
function MsgError($mensaje)
{
    return"<span class=\"ocultar_msg iconoMsgError rojo\" title=\"$mensaje\">*</span>";
}

/**
 * Genera un String HTML con un Mensaje exitoso
 * @param type $mensaje Titulo del mensaje
 * @return String Retorna un span con los estilos armados
 */
function MsgOK($mensaje)
{
    return"<span class=\"ocultar_msg iconoMsgOk\" title=\"$mensaje\">Ok</span>";
}

/**
 * Valida un Integer y en caso de ser valido retorna el valor de la variable<br>
 * En caso de ser incorrecto retorna 0
 * 
 * @param Integer $id ID para sanitizar
 * @return Integer En caso de ser valido retorna el valor recibido<br>
 *          En caso de ser incorrecto retorna "0"
 */
function SanitizaID($id)
{
    if ((trim($id)) == 'nulo')
    {
        return $id;
    }
    else
    if (is_numeric($id) && preg_match('/^[0-9]+$/', $id))
    {
        return $id;
    }
    else
    {
        return 0;
    }
}

/**
 * Compara dos cadenas y en caso de ser coincidentes retorna un string<br> 
 * para seleccionar un Input Select de un formulario
 * @param String $cadena_uno Primera cadena para comparar
 * @param String $cadena_dos Segunda cadena para comparar
 * @return string Solo en caso de ser iguales retorna selected="selected"
 */
function MarcaSelected($cadena_uno, $cadena_dos)
{
    if ($cadena_uno==$cadena_dos)
    {
        return " selected=\"selected\" ";
    }
}

/**
 * Compara dos cadenas y en caso de ser coincidentes retorna un string<br> 
 * para seleccionar un Checkbox de un formulario
 * @param String $cadena_uno Primera cadena para comparar
 * @param String $cadena_dos Segunda cadena para comparar
 * @return string Solo en caso de ser iguales retorna checked="checked"
 */
function MarcarCheckbox($cadena_uno, $cadena_dos)
{
    if ($cadena_uno==$cadena_dos)
    {
        return " checked=\"checked\" ";
    }
}

function ValidaRFC($rfc)
{
    $cadena = false;
    if (preg_match("/^(([ÑA-Z|ña-z|&]{3}|[A-Z|a-z]{4})\d{2}((0[1-9]|1[012])(0[1-9]|1\d|2[0-8])|(0[13456789]|1[012])(29|30)|(0[13578]|1[02])31)(\w{2})([A|a|0-9]{1}))$|^(([ÑA-Z|ña-z|&]{3}|[A-Z|a-z]{4})([02468][048]|[13579][26])0229)(\w{2})([A|a|0-9]{1})$/",$rfc))
    {
        $cadena = true;
    } 
    return $cadena;
}

function ValidaCURP($curp)
{
    $cadena= false;
    if (preg_match("/^([A-Z]{4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM](AS|BC|BS|CC|CL|CM|CS|CH|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[A-Z]{3}[0-9A-Z]\d)$/",$curp))
    {$cadena= true;}
    return $cadena;
}

function ValidaNumeroTelefono($numero)
{
    $valido =false; 
    if(preg_match("/^([0-9]){10}+$/",$numero))
    {
        $valido=true;
    } 
    return $valido;
}

function ValidaCP($numero)
{
    $valido = false;
    if(preg_match("/^([0-9]){5}+$/",$numero))
    {
        $valido = true;
    }
    return $valido;
}

function Valida_canon_email($str)
{
    return (false !== filter_var($str, FILTER_VALIDATE_EMAIL));
}

function Valida_MX_valid_email($str)
{
    $result = (false !== filter_var($str, FILTER_VALIDATE_EMAIL));
    if ($result)
    {
        $result = explode('@',$str);
        $domain= $result[1];
        $result = checkdnsrr($domain, 'MX');
    }
    return $result;
}

function ValidaIPV4($ip)
{
    $cadena= false; 
    if (preg_match("/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/",$ip))
    {
        $cadena= true;
    } 
    return $cadena; 
}

function GetIpReal()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
    {
        return $_SERVER['HTTP_CLIENT_IP']; 
    }
    else
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) 
    {
        return $_SERVER['HTTP_X_FORWARDED_FOR']; return $_SERVER['REMOTE_ADDR'];
    }
}

function FormatoUrlAmigable($url)
{
    $url=trim($url); 
    $url = strtolower($url); 
    $find = array('á', 'é', 'í', 'ó', 'ú', 'ñ','-','_'); 
    $repl = array('a', 'e', 'i', 'o', 'u', 'n',' ',' '); 
    $url = str_replace ($find, $repl, $url); 
    $find = array(' ', '&', '\r\n', '\n', '+'); 
    $url = str_replace ($find, '-', $url); 
    $find = array('/[^a-z0-9\-<>.()]/', '/[\-]+/', '/<[^>]*>/'); 
    $repl = array('', '-', ''); 
    $url = preg_replace ($find, $repl, $url); 
    $url=trim($url); 
    return $url; 
}

/**
 * Formatea una fecha 1000-01-01 00:00
 * @param Date $fecha Solo admite el formato 1000-01-01 00:00
 * @return String Salida con formato dd/mm/aaaaa hh:mm
 */
function ddmmaaaahh($fecha)
{
    $hhmm="";
    $dd=substr($fecha, 8, 2);
    $mm=substr($fecha, 5, 2);
    $aaaa=substr($fecha, 0, 4);
    $cadena= "$dd/$mm/$aaaa";
    if ($aaaa=="1000"){$cadena="";}
    if ((strlen($fecha)==19) AND ($cadena<>""))
    {
        $hora=substr($fecha, 11,2);
        $min=substr($fecha, 14, 2);
        if($hora<=12)
        {
            $meridiano=" a.m.";
        }
        else
        if ($hora>=13)
        {
            $hora = $hora-12; 
            $meridiano = " p.m.";
        }
        $hhmm=" ".sprintf("%02s", $hora).":$min $meridiano";
    }
    return $cadena.$hhmm;
}

function imprimirElementosPOST() {
    $cadena ='';
    // Verificar si hay datos enviados por POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Iterar sobre todos los elementos del arreglo POST
        foreach ($_POST as $nombre => $valor) {
            // Imprimir el nombre del elemento junto con su valor
            $cadena .= "$nombre: $valor <br>";
        }
    } else {
        // Si no hay datos enviados por POST, imprimir un mensaje
        $cadena =  "No se han recibido datos por POST.";
    }
    return $cadena;
}

function imprimirElementosGET() {
// Función que obtiene y procesa la URL
// Verificar si la URL está presente en la solicitud GET
    if (isset($_GET['url'])) {
        // Obtener la URL desde los parámetros GET
        $url = $_GET['url'];
        
        // Dividir la URL en partes usando '/' como delimitador
        $partes = explode('/', $url);
        // Listar cada parte obtenida
        $id = 0;
        foreach ($partes as $parte) {
            
            echo "$id . $parte " . '<br>';

            $GLOBALS['URL_'. $id] = $parte;
            $id += 1;

        }
    } else {
        echo 'No se han recibido datos por URL.';
    }
}