<?php

namespace App\Model;

use App\Lib\Database;
use App\Lib\Respuesta;
use PDO;
use Exception;

class Dispositivo
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
            $stm = $this->conn->prepare("SELECT id, nombre, ip, usuario, PASSWORD, tipo, ubicacion, imagen FROM Dispositivos ORDER BY $orderby");
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
            $sql = "SELECT id, nombre, ip, usuario, PASSWORD, tipo, ubicacion, imagen FROM Dispositivos where id = $id";
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

    public function buscarNombre($string)
    {
        try{
            $sql = "SELECT id as value, nombre as label FROM Dispositivos where nombre like '%$string%' ORDER BY nombre";
            $stm=$this->conn->prepare($sql);
            $stm->execute();
            $tuples = $stm->fetchAll();
            return $tuples;

        }catch(Exception $e){
            $this->respuesta->setCorrecta(false, "Error get ID: ".$e->getMessage());
            return $this->respuesta;
        }
    }

    public function cercarNom($string) //Unused
    {
        try {
            $sql = "SELECT id, nombre, ip, usuario, PASSWORD, tipo, ubicacion, imagen FROM Dispositivos where nombre like '%$string%' ORDER BY nombre";
            $stm = $this->conn->prepare($sql);
            $stm->execute();
            $tuples = $stm->fetchAll();
            return $tuples;

        } catch (Exception $e) {
            $this->respuesta->setCorrecta(false, "Error get ID: " . $e->getMessage());
            return $this->respuesta;
        }
    }



    public function insert($data)
    {
        try {
            $nom_dis = !empty($data['nom_dis']) ? htmlspecialchars($data['nom_dis']) : NULL; //Si no hay datos los deja en vacio
            $ip_dis = !empty($data['ip_dis']) ? htmlspecialchars($data['ip_dis']) : NULL;
            $user_dis = !empty($data['user_dis']) ? htmlspecialchars($data['user_dis']) : NULL;
            $pass_dis = !empty($data['pass_dis']) ? htmlspecialchars($data['pass_dis']) : NULL;
            $tip_dis = !empty($data['tip_dis']) ? htmlspecialchars($data['tip_dis']) : NULL;
            $ubi_dis = !empty($data['ubi_dis']) ? htmlspecialchars($data['ubi_dis']) : NULL;
            $img_dis = !empty($data['img_dis']) ? $data['img_dis'] : NULL;

            $sql = "INSERT INTO Dispositivos
                            (nombre, ip, usuario, PASSWORD, tipo, ubicacion, imagen)
                            VALUES (:nom_dis,:ip_dis,:user_dis,:pass_dis,:tip_dis,:ubi_dis,:img_dis)";

            $stm = $this->conn->prepare($sql);
            $stm->bindValue(':nom_dis', $nom_dis);
            $stm->bindValue(':ip_dis', !empty($ip_dis) ? $ip_dis : NULL, PDO::PARAM_STR);
            $stm->bindValue(':user_dis', !empty($user_dis) ? $user_dis : NULL, PDO::PARAM_STR);
            $stm->bindValue(':pass_dis', !empty($pass_dis) ? $pass_dis : NULL, PDO::PARAM_STR);
            $stm->bindValue(':tip_dis', !empty($tip_dis) ? $tip_dis : NULL, PDO::PARAM_STR);
            $stm->bindValue(':ubi_dis', !empty($ubi_dis) ? $ubi_dis : NULL, PDO::PARAM_STR);
            $stm->bindValue(':img_dis', !empty($img_dis) ? $img_dis : NULL, PDO::PARAM_STR);
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
            $id_dis = $data['id'];
            $nom_dis = !empty($data['nom_dis']) ? htmlspecialchars($data['nom_dis']) : NULL;
            $ip_dis = !empty($data['ip_dis']) ? htmlspecialchars($data['ip_dis']) : NULL;
            $user_dis = !empty($data['user_dis']) ? htmlspecialchars($data['user_dis']) : NULL;
            $pass_dis = !empty($data['pass_dis']) ? htmlspecialchars($data['pass_dis']) : NULL;
            $tip_dis = !empty($data['tip_dis']) ? htmlspecialchars($data['tip_dis']) : NULL;
            $ubi_dis = !empty($data['ubi_dis']) ? htmlspecialchars($data['ubi_dis']) : NULL;
            $img_dis = !empty($data['img_dis']) ? $data['img_dis'] : NULL;

            $sql = "UPDATE `Dispositivos` SET `nombre` = :nom_dis, `ip` = :ip_dis, `usuario` = :user_dis, `PASSWORD` = :pass_dis, `tipo` = :tip_dis, `ubicacion` = :ubi_dis, `imagen` = :img_dis WHERE `Dispositivos`.`id` = :id_dis; ";

            $stm = $this->conn->prepare($sql);
            $stm->bindValue(':id_dis', $id_dis);
            $stm->bindValue(':nom_dis', $nom_dis);
            $stm->bindValue(':ip_dis', !empty($ip_dis) ? $ip_dis : NULL, PDO::PARAM_STR);
            $stm->bindValue(':user_dis', !empty($user_dis) ? $user_dis : NULL, PDO::PARAM_STR);
            $stm->bindValue(':pass_dis', !empty($pass_dis) ? $pass_dis : NULL, PDO::PARAM_STR);
            $stm->bindValue(':tip_dis', !empty($tip_dis) ? $tip_dis : NULL, PDO::PARAM_STR);
            $stm->bindValue(':ubi_dis', !empty($ubi_dis) ? $ubi_dis : NULL, PDO::PARAM_STR);
            $stm->bindValue(':img_dis', !empty($img_dis) ? $img_dis : NULL, PDO::PARAM_STR);
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
            $sql = "DELETE FROM `Historicos` WHERE fk_dispositivo=:id_dis";

            $stm = $this->conn->prepare($sql);
            $stm->bindValue(':id_dis', $id);
            $stm->execute();

            $sql = "DELETE FROM `Dispositivos` WHERE id=:id_dis";

            $stm = $this->conn->prepare($sql);
            $stm->bindValue(':id_dis', $id);
            $stm->execute();
            $this->respuesta->setCorrecta(true);
            return $this->respuesta;
        } catch (Exception $e) {
            $this->respuesta->setCorrecta(false, "Error eliminant: " . $e->getMessage());
            return $this->respuesta;
        }
    }

    public function filtra($where, $orderby, $offset, $count) //Unused
    {
        try {
            $where = (!empty($where) ? "WHERE " . $where : "");
            $orderby = (!empty($orderby) ? $orderby : "id");
            $offset = (!empty($offset) ? $offset : "0");
            $count = (!empty($count) ? $count : "20");

            $result = array();
            $sql = "SELECT id, nombre, ip, usuario, PASSWORD, tipo, ubicacion, imagen FROM Dispositivos $where ORDER BY $orderby LIMIT $offset,$count";
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
