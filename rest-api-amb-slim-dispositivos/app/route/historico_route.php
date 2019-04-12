<?php

use App\Model\Historico;

$app->group('/historico/', function () {

    $this->get('', function ($req, $res, $args) {
        $obj = new Historico();
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
        $obj = new Historico();
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
        $obj = new Historico();
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
        $atributos = $req->getParsedBody();  //llista atributos del Historico
        $atributos["id"] = $args["id"];     // le ponemos la id
        $obj = new Historico();
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
        $obj = new Historico();
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

$app->group('/historico_dispositivo/', function () {
    $this->get('{id}', function ($req, $res, $args) {
        $obj = new Historico();
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
                json_encode(
                    $obj->getDispositivos($args["id"])
                )
            );
    });
});

$app->group('/historico_tecnico/', function () {
    $this->get('{id}', function ($req, $res, $args) {
        $obj = new Historico();
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
                json_encode(
                    $obj->getTecnicos($args["id"])
                )
            );
    });
});

