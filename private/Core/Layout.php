<?php

function VersionCache()
{
    $cadena = date('YmdHis');

    if (!(is_file("doctos/VerCache.txt")))
    {        
        $file = fopen("doctos/VerCache.txt", "w"); 
        fwrite($file, $cadena);
        fclose($file);
    }
    
    if (is_file("doctos/VerCache.txt"))
    {
        $file = fopen("doctos/VerCache.txt", "r");
        while (!feof($file))
        {
            $cadena = fgets($file);
        }
        fclose($file);
    }
    return $cadena;
}

function IntegraLayout($layout = '', $link = '', $titulo = '', $contenido = '')
{
    $desarollo = ''; 
    $VerCache = '';
    if (isset($GLOBALS['Link'])){$link = $GLOBALS['Link'];}
    if (isset($GLOBALS['TituloPagina'])){$titulo = $GLOBALS['TituloPagina'];}
    if (isset($GLOBALS['Contenido'])){$contenido = $GLOBALS['Contenido'];}
  
        if (isset($GLOBALS['desarrollo']))
    {
        /* Si esta configurado $GLOBALS['desarrollo']=1 en ConfigBD.php 
         * sobreescribe dinamicamente la fecha del cache de los archivos .js y .css
         * para indicar descargar siempre los archivos y no utilizar la cache del navegador
        */

        if ($GLOBALS['desarrollo'] == 1)
        {
            $VerCache = '?' . date('YmdHis');
            $desarollo = " class=\"desarollo\""; 
        }
        else
        if ($GLOBALS['desarrollo'] <> 1)
        {
            $VerCache = '?' . VersionCache(); 
            $desarollo = '';
        }
    }
    
    $diccionario = array(
        "{desarollo}" => $desarollo,
        "{copy}" => $GLOBALS['copy'],    
        "{link}" => $link,
        "{titulo}" => $titulo,
        "{contenido}" => $contenido,
        "{UrlBase}" => $GLOBALS['OrigenURL'],
        "{VerCache}" => $VerCache,
    );
    $layout = str_replace(array_keys($diccionario), array_values($diccionario),$layout);
    return $layout;
}