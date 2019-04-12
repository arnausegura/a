<?php

use App\Model\Tecnico;

$app->group('/tecnico/', function () {

    $this->get('', function ($req, $res, $args) {
        $obj = new Tecnico();
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
                json_encode(
                    $obj->getAll()
                )
            );
    });

    $this->get('{id}', function ($req, $res, $args) {
        $obj = new Tecnico();
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
                json_encode(
                    $obj->get($args["id"])
                )
            );
    });

    $this->post('', function ($req, $res, $args) {
        $atributs = $req->getParsedBody();
        $obj = new Tecnico();
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
                json_encode(
                    $obj->insert($atributs)
                )
            );
    });

    $this->put('{id}', function ($req, $res, $args) {
        $atributos = $req->getParsedBody();  //llista atributos del Tecnico
        $atributos["id"] = $args["id"];     // le ponemos la id
        $obj = new Tecnico();
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
                json_encode(
                    $obj->update($atributos)
                )
            );
    });

    $this->delete('{id}', function ($req, $res, $args) {
        $obj = new Tecnico();
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
                json_encode(
                    $obj->delete($args["id"])
                )
            );
    });

});

$app->group('/nombreTecnico/', function () {

    $this->get('{string}', function ($req, $res, $args) {
        $obj = new Tecnico();
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
                json_encode(
                    $obj->buscarNombre($args["string"])
                )
            );
    });

});
