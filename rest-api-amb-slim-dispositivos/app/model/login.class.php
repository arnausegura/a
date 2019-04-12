<?php

namespace App\Model;

use App\Lib\Database;
use App\Lib\Respuesta;
use PDO;
use Exception;

class Login
{

    private $conn;       //connexió a la base de dades (PDO)
    private $respuesta;   // respuesta

    public function __CONSTRUCT()
    {
        $objectebd = Database::getInstance();
        $this->conn = $objectebd->getConnection();
        $this->respuesta = new Respuesta();
    }

    public function post($data)
    {
        try {
            $usuario = !empty($data['usuario']) ? htmlspecialchars($data['usuario']) : NULL; //Si no hay datos los deja en vacio
            $password = !empty($data['password']) ? htmlspecialchars($data['password']) : NULL;
            $sql = "SELECT usuario, PASSWORD, id FROM Tecnicos WHERE usuario= :usuario and PASSWORD= :password ";
            $stm = $this->conn->prepare($sql);
            $stm->bindValue(':usuario', !empty($usuario) ? $usuario : NULL, PDO::PARAM_STR);
            $stm->bindValue(':password', !empty($password) ? $password : NULL, PDO::PARAM_STR);
            $stm->execute();
            if ($tupla = $stm->fetch()) {
                // inicia session
                $_SESSION["usuario"] = $tupla["usuario"];
                $this->respuesta->setCorrecta(true); // La respuesta es correcta
                $this->respuesta->setDatos($tupla["id"]);
            } else {
                $this->respuesta->setCorrecta(false, "login incorrecto");
            }
            return $this->respuesta;
        } catch (\Exception $e) {
            $this->respuesta->setCorrecta(false, $e->getMessage());
            return $this->respuesta;
        }
    }

    public function comprobarUsuario() //Unused
    {
        try {
            $usuario = !empty($_SESSION["usuario"]) ? $_SESSION["usuario"] : NULL;
            if ($usuario==NULL){
                $this->respuesta->setCorrecta(false);
                return $this->respuesta;
            }
            $sql = "SELECT usuario FROM Tecnicos where usuario = :usuario";
            $stm = $this->conn->prepare($sql);
            $stm->bindValue(':usuario', !empty($usuario) ? $usuario : NULL, PDO::PARAM_STR);
            $stm->execute();
            $row = $stm->fetchAll();
            $this->respuesta->setDatos($row);
            $this->respuesta->setCorrecta(true);
            return $this->respuesta;

        } catch (Exception $e) {
            $this->respuesta->setCorrecta(false, "Error get ID: " . $e->getMessage());
            return $this->respuesta;
        }
    }

}
