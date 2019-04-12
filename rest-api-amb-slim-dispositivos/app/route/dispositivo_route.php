<?php

use App\Model\Dispositivo;

$app->group('/dispositivo/', function () {

    $this->get('', function ($req, $res, $args) {
        $obj = new Dispositivo();
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
        $obj = new Dispositivo();
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
        $atributs = $req->getParsedBody();  //llista atributs del client
        $obj = new Dispositivo();
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
        $atributos = $req->getParsedBody();  //llista atributos del dispositivo
        $atributos["id"] = $args["id"];     // le ponemos la id
        $obj = new Dispositivo();
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
        $obj = new Dispositivo();
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

$app->group('/nombreDispositivo/', function () {

    $this->get('{string}', function ($req, $res, $args) {
        $obj = new Dispositivo();
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
