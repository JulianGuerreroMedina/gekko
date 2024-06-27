<?php

Class Db
{
    private $link; 
    private $resultado; 
    private $array; 
    static $_instancia; 

    protected $bd_host = bd_host;
    protected $bd_username = bd_username;
    protected $bd_passwd = bd_passwd;
    protected $bd_dbname = bd_dbname;
    protected $bd_port = bd_port;

    private function __construct()
    {
        $this->conectar();
    }

    private function __clone(){}

    public static function getInstancia()
    {
        if(!(self::$_instancia instanceof self))
        {
            self::$_instancia=new self();} return self::$_instancia;        
    }

    private function conectar()
    {
        $this->link=mysqli_connect(
            $this->bd_host, 
            $this->bd_username,
            $this->bd_passwd,
            $this->bd_dbname,
            $this->bd_port
        ); 
        
        $this->link->set_charset('utf8'); 
    }

    public function ejecutar($consulta_sql)
    {
        $this->resultado=mysqli_query($this->link,$consulta_sql); 
        if(!($this->resultado))
        {
            if (($GLOBALS['desarrollo'])==1)
            {
                error_log(mysqli_error($this->link)."\r". $consulta_sql."\r");
            }
        }
        return $this->resultado;
    }

    public function obtener_fila($resultado,$fila)
    {
        if ($fila==0)
        {
            $this->array=@mysqli_fetch_array($resultado); 
        }
        else 
        {
            mysqli_data_seek($resultado,$fila); 
            $this->array=mysqli_fetch_array($resultado);
        }
        return $this->array; 
    }
        
    public function alter()
    {
        if(!($this->resultado = $this->query($this->sql)))
        {
            $this->lastError = $this->error; 
            return false; 
        }
        self::$sqlQueries[] = $this->sql; 
        return true;
    }
    
    public function CerrarDB()
    {
        $this->close();
    }
    
    public function UltimoIdCreado()
    {
        return mysqli_insert_id($this->link);
    }
}

function UltimoID()
{
    $bd=Db::getInstancia(); 
    $resultado=$bd->UltimoIdCreado(); 
    return $resultado;
}
   
function RegresaCamposEspecificos($Consulta,$tipo)
{
    if (count($Consulta)>=1)
    {
        foreach ($Consulta as $Consulta): 
            $cadena=$Consulta[$tipo]; 
        endforeach; 
        return $cadena;
    }
}