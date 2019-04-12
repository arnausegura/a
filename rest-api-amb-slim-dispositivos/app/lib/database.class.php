<?php
namespace App\Lib;
use PDO;

class Database {

  private static $instance = null;  //Intancia del objeto
  private $pdo;    //conexion a la base de datos
  private $host = BIB_HOST;  
  private $user = BIB_USER;  
  private $pass = BIB_PASS;     
  private $dbname = BIB_BBDD;
  
  private function __construct() {
    $this->pdo = new PDO("mysql:host={$this->host}; dbname={$this->dbname}", $this->user,$this->pass,
                                    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);        // activam excepcions
    $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);     // per defecte fetch object                                                        
  }

  public static function getInstance() {
    // da la instancia y si no existe la crea
    if(!self::$instance) { 
        self::$instance = new Database();  
    }
    return self::$instance;
  }

  public function getConnection() {  
      return $this->pdo;  
  }
} 