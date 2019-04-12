<?php

namespace App\Lib;

class Respuesta
{
    public $datos = null;     //Los datos que ha generado la peticion
    public $correcta = false;    //Dice si la operacion a funcionado correctamente
    public $mensaje = '';

    public function setCorrecta($correcta, $m = '')
    {
        $this->correcta = $correcta;
        $this->mensaje = $m;

        if (!$correcta && $m = '') {
            $this->mensaje = 'Error inesperado, contacte con los desarolladores';
        }
    }

    public function setDatos($datos)
    {
        $this->datos = $datos;
    }

    public function getDatos($datos)
    {
        return $this->datos;
    }

}