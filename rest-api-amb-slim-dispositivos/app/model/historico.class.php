<?php

namespace App\Model;

use App\Lib\Database;
use App\Lib\Respuesta;
use PDO;
use Exception;

class Historico
{

    private $conn;       //connexió a la base de dades (PDO)
    private $respuesta;   // respuesta

    public function __CONSTRUCT()
    {
        $this->conn = Database::getInstance()->getConnection();
        $this->respuesta = new Respuesta();
    }

    public function getAll($orderby = "fecha DESC")
    {
        try {
            $stm = $this->conn->prepare("SELECT id, fk_dispositivo, fk_tecnico, fecha, descripcion FROM Historicos ORDER BY $orderby");
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
            $sql = "SELECT id, fk_dispositivo, fk_tecnico, fecha, descripcion FROM Historicos where id = $id";
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

    public function getDispositivosId($id) //Unused, Solo pide a una tabla
    {
        try {
            $sql = "SELECT id, fk_dispositivo, fk_tecnico, fecha, descripcion FROM Historicos where fk_dispositivo = $id";
            $stm = $this->conn->prepare($sql);
            $stm->execute();
            $tuples = $stm->fetchAll();
            $this->respuesta->setDatos($tuples);
            $this->respuesta->setCorrecta(true);
            return $this->respuesta;

        } catch (Exception $e) {
            $this->respuesta->setCorrecta(false, "Error get ID: " . $e->getMessage());
            return $this->respuesta;
        }
    }

    public function getDispositivos($id) // junta la tabla de dispositivos y tecnicos para obtener su usuario
    {
        try {
            $sql = "SELECT Historicos.fecha,Tecnicos.usuario,Historicos.descripcion, Historicos.id FROM Historicos JOIN Tecnicos on Historicos.fk_tecnico = Tecnicos.id where fk_dispositivo = $id ORDER BY Historicos.fecha DESC";
            $stm = $this->conn->prepare($sql);
            $stm->execute();
            $tuples = $stm->fetchAll();
            $this->respuesta->setDatos($tuples);
            $this->respuesta->setCorrecta(true);
            return $this->respuesta;

        } catch (Exception $e) {
            $this->respuesta->setCorrecta(false, "Error get ID: " . $e->getMessage());
            return $this->respuesta;
        }
    }

    public function getTecnicosId($id) //Unused, Solo pide a una tabla
    {
        try {
            $sql = "SELECT id, fk_dispositivo, fk_tecnico, fecha, descripcion FROM Historicos where fk_tecnico = $id";
            $stm = $this->conn->prepare($sql);
            $stm->execute();
            $tuples = $stm->fetchAll();
            $this->respuesta->setDatos($tuples);
            $this->respuesta->setCorrecta(true);
            return $this->respuesta;

        } catch (Exception $e) {
            $this->respuesta->setCorrecta(false, "Error get ID: " . $e->getMessage());
            return $this->respuesta;
        }
    }

    public function getTecnicos($id) // junta la tabla de dispositivos y tecnicos para obtener el dispositivo
    {
        try {
            $sql = "SELECT Historicos.fecha, dispositivos.nombre,Historicos.descripcion, Historicos.id FROM Historicos JOIN dispositivos on Historicos.fk_dispositivo = dispositivos.id where fk_tecnico = $id Order By Historicos.fecha DESC ";
            $stm = $this->conn->prepare($sql);
            $stm->execute();
            $tuples = $stm->fetchAll();
            $this->respuesta->setDatos($tuples);
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
            $fk_dis = !empty($data['fk_dis']) ? $data['fk_dis'] : NULL; //Si no hay datos los deja en vacio
            $fk_tec = !empty($data['fk_tec']) ? $data['fk_tec'] : NULL;
            $fec_his = !empty($data['fec_his']) ? $data['fec_his'] : NULL;
            $des_his = !empty($data['des_his']) ? htmlspecialchars($data['des_his']) : NULL;

            $sql = "INSERT INTO Historicos
                            (fk_dispositivo, fk_tecnico, fecha, descripcion)
                            VALUES (:fk_dis,:fk_tec,:fec_his,:des_his)";

            $stm = $this->conn->prepare($sql);
            $stm->bindValue(':fk_dis', !empty($fk_dis) ? $fk_dis : NULL, PDO::PARAM_STR);
            $stm->bindValue(':fk_tec', !empty($fk_tec) ? $fk_tec : NULL, PDO::PARAM_STR);
            $stm->bindValue(':fec_his', !empty($fec_his) ? $fec_his : NULL, PDO::PARAM_STR);
            $stm->bindValue(':des_his', !empty($des_his) ? $des_his : NULL, PDO::PARAM_STR);
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
            $id_his = $data['id_his'];
            $fk_dis = !empty($data['fk_dis']) ? $data['fk_dis'] : NULL; //Si no hay datos los deja en vacio
            $fk_tec = !empty($data['fk_tec']) ? $data['fk_tec'] : NULL;
            $fec_his = !empty($data['fec_his']) ? $data['fec_his'] : NULL;
            $des_his = !empty($data['des_his']) ? htmlspecialchars($data['des_his']) : NULL;
            //$des_his =  htmlspecialchars($des_his);

            $sql = "UPDATE `Historicos` SET `fk_dispositivo` = :fk_dis, `fk_tecnico` = :fk_tec, `fecha` = :fec_his, `descripcion` = :des_his WHERE `Historicos`.`id` = :id_his;";

            $stm = $this->conn->prepare($sql);
            $stm->bindValue(':id_his', $id_his);
            $stm->bindValue(':fk_dis', !empty($fk_dis) ? $fk_dis : NULL, PDO::PARAM_STR);
            $stm->bindValue(':fk_tec', !empty($fk_tec) ? $fk_tec : NULL, PDO::PARAM_STR);
            $stm->bindValue(':fec_his', !empty($fec_his) ? $fec_his : NULL, PDO::PARAM_STR);
            $stm->bindValue(':des_his', !empty($des_his) ? $des_his : NULL, PDO::PARAM_STR);
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
            $sql = "DELETE FROM `Historicos` WHERE id=:id_his";

            $stm = $this->conn->prepare($sql);
            $stm->bindValue(':id_his', $id);
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
            $orderby = (!empty($orderby) ? $orderby : "id ASC");
            $offset = (!empty($offset) ? $offset : "0");
            $count = (!empty($count) ? $count : "20");

            $result = array();
            $sql = "SELECT id, nombre, ip, usuario, PASSWORD, tipo, ubicacion, imagen FROM Historicos $where ORDER BY $orderby LIMIT $offset,$count";
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

