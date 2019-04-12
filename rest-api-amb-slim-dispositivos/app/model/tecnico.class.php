<?php

namespace App\Model;

use App\Lib\Database;
use App\Lib\Respuesta;
use PDO;
use Exception;

class Tecnico
{

    private $conn;       //connexió a la base de dades (PDO)
    private $respuesta;   // respuesta

    public function __CONSTRUCT()
    {
        $this->conn = Database::getInstance()->getConnection();
        $this->respuesta = new Respuesta();
    }

    public function getAll($orderby = "nombre")
    {
        try {
            $stm = $this->conn->prepare("SELECT id, nombre, usuario, PASSWORD FROM Tecnicos ORDER BY $orderby");
            $stm->execute();
            $tuples = $stm->fetchAll();
            $this->respuesta->setDatos($tuples);    // array de tuples
            $this->respuesta->setCorrecta(true);       // La respuesta es correcta
            return $this->respuesta;
        } catch (Exception $e) {   // Como sucede un error passaremos la variable de correcta a falso
            $this->respuesta->setCorrecta(false, $e->getMessage());
            return $this->respuesta;
        }
    }


    public function get($id)
    {
        try {
            $sql = "SELECT id, nombre, usuario, PASSWORD FROM Tecnicos where id = $id";
            $stm = $this->conn->prepare($sql);
            $stm->execute();
            $row = $stm->fetch();
            $this->respuesta->setDatos($row);
            $this->respuesta->setCorrecta(true);
            return $this->respuesta;

        } catch (Exception $e) {
            $this->respuesta->setCorrecta(false, "Error get ID: " . $e->getMessage());
            return $this->respuesta;
        }
    }

    public function insert($data)
    {
        try {
            $nom_tec = !empty($data['nom_tec']) ? htmlspecialchars($data['nom_tec']) : NULL; //Si no hay datos los deja en vacio
            $user_tec = !empty($data['user_tec']) ? $data['user_tec'] : NULL;
            $pass_tec =!empty($data['pass_tec']) ? $data['pass_tec'] : NULL;

            $sql = "INSERT INTO Tecnicos
                            (nombre, usuario, PASSWORD)
                            VALUES (:nom_tec,:user_tec,:pass_tec)";

            $stm = $this->conn->prepare($sql);
            $stm->bindValue(':nom_tec', !empty($nom_tec) ? $nom_tec : NULL, PDO::PARAM_STR);
            $stm->bindValue(':user_tec', !empty($user_tec) ? $user_tec : NULL, PDO::PARAM_STR);
            $stm->bindValue(':pass_tec', !empty($pass_tec) ? $pass_tec : NULL, PDO::PARAM_STR);
            $stm->execute();

            $this->respuesta->setCorrecta(true, $stm->rowCount());
            return $this->respuesta;
        } catch (Exception $e) {
            $this->respuesta->setCorrecta(false, "Error insertando: " . $e->getMessage());
            return $this->respuesta;
        }
    }

    public function update($data)
    {
        try {
            $id_his = $data['id_tec'];
            $nom_tec = !empty($data['nom_tec']) ? htmlspecialchars($data['nom_tec']) : NULL; //Si no hay datos los deja en vacio
            $user_tec = !empty($data['user_tec']) ? $data['user_tec'] : NULL;
            $pass_tec = !empty($data['pass_tec']) ? $data['pass_tec'] : NULL;

            $sql = "UPDATE `Tecnicos` SET `nombre` = :nom_tec, `usuario` = :user_tec, `PASSWORD` = :pass_tec WHERE `Tecnicos`.`id` = :id_tec;";

            $stm = $this->conn->prepare($sql);
            $stm->bindValue(':id_tec', $id_his);
            $stm->bindValue(':nom_tec', !empty($nom_tec) ? $nom_tec : NULL, PDO::PARAM_STR);
            $stm->bindValue(':user_tec', !empty($user_tec) ? $user_tec : NULL, PDO::PARAM_STR);
            $stm->bindValue(':pass_tec', !empty($pass_tec) ? $pass_tec : NULL, PDO::PARAM_STR);
            $stm->execute();

            $this->respuesta->setCorrecta(true);
            return $this->respuesta;
        } catch (Exception $e) {
            $this->respuesta->setCorrecta(false, "Error mofificant: " . $e->getMessage());
            return $this->respuesta;
        }
    }

    public function delete($id)
    {
        try {
            $sql = "DELETE FROM `Tecnicos` WHERE id=:id_tec";

            $stm = $this->conn->prepare($sql);
            $stm->bindValue(':id_tec', $id);
            $stm->execute();
            $this->respuesta->setCorrecta(true);
            return $this->respuesta;
        } catch (Exception $e) {
            $this->respuesta->setCorrecta(false, "Error eliminant: " . $e->getMessage());
            return $this->respuesta;
        }
    }

    public function buscarNombre($string)
    {
        try{
            $sql = "SELECT id as value, nombre as label FROM Tecnicos where nombre like '%$string%' ORDER BY nombre";
            $stm=$this->conn->prepare($sql);
            $stm->execute();
            $tuples = $stm->fetchAll();
            return $tuples;

        }catch(Exception $e){
            $this->respuesta->setCorrecta(false, "Error get ID: ".$e->getMessage());
            return $this->respuesta;
        }
    }

    public function filtra($where, $orderby, $offset, $count)
    {
        try {
            $where = (!empty($where) ? "WHERE " . $where : "");
            $orderby = (!empty($orderby) ? $orderby : "id");
            $offset = (!empty($offset) ? $offset : "0");
            $count = (!empty($count) ? $count : "20");

            $sql = "SELECT id, nombre, usuario, PASSWORD FROM Tecnicos $where ORDER BY $orderby LIMIT $offset,$count";
            $stm = $this->conn->prepare($sql);
            $stm->execute();
            $tuples = $stm->fetchAll();
            $this->respuesta->setDatos($tuples);
            $this->respuesta->setCorrecta(true);
            return $this->respuesta;
        } catch (Exception $e) {
            $this->respuesta->setCorrecta(false, "Error cercant: " . $e->getMessage());
            return $this->respuesta;
        }
    }
}
