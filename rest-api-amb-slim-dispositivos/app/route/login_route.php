<?php
use App\Model\Login;

$app->group('/login/', function () {
    
    $this->post('', function ($req, $res, $args) {
            $atributs = $req->getParsedBody();  //llista atributs del client
            $obj = new Login();
            return $res
               ->withHeader('Content-type', 'application/json')
               ->getBody()
               ->write(
                json_encode(
                    $obj->post($atributs)
                )
            );             
    });
           
});

$app->group('/validacion/', function () {

    $this->get('', function ($req, $res, $args) {
        $atributs = $req->getParsedBody();  //llista atributs del client
        $obj = new Login();
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
                json_encode(
                    $obj->comprobarUsuario()
                )
            );
    });

});
