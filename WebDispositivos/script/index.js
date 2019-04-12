jQuery(document).ready(function () {
    /*$('#login').prop("disabled", true);
    cargarDispositivos();*/
});
// 192.168.244.76 Local
// 192.168.244.85 Server Pro
$globalUrl = "http://192.168.244.85/rest-api-amb-slim-dispositivos/public/";
$IdPropia = 0;
//$.md5('value');
//confirm('Some message');


//var ping = new Date; $.ajax({ type: "POST", url: "server.php", data: {}, cache:false, success: function(output){ ping = new Date - ping; } });
function loginTecnico() {
    $.ajax({
        url: $globalUrl + 'login/',
        type: 'POST',
        dataType: 'json',
        data: {usuario: $('[name="usuario"]').val(), password: $.md5($('[name="password"]').val())},
        success: function (respuesta) {
            if (respuesta["correcta"]) {
                $IdPropia = respuesta["datos"][0];
                cargarDispositivos();
            } else {
                $('#mensajes').text('Introduce una contraseña y usuario valido');
            }
        },
        error: function () {
            $('#mensajes').text('Error carrgando lista');
        }
    });
}

function cargarDispositivos() {
        // Mostrara la lista de dispositivos
        $.ajax({
        url: $globalUrl + 'dispositivo/',
        type: 'GET',
        async: false,
        dataType: 'json',
        success: function (respuesta) {
            if (respuesta["correcta"]) {
                $("#contenedor").fadeOut(100, function () {
                    $('#tituloOpcion').text('Llista Dispositivos');
                    $("#contenido").empty();
                    $('#mensajes').text('');
                    $("#contenido").append('<div class="divTableBody">' +

                        '<div class="divTableRow"><div class="divTableCellPetit menu">' +
                        '</div><div class="divTableCellPetit menu">' +
                        '</div><div class="divTableCellPetit menu">' +
                        '</div><div class="divTableCellPetit menu"><button type="button" onclick="cargarDispositivos()">Recargar</button>' +
                        '</div><div class="divTableCellPetit menu"><button type="button" onclick="nuevoDispositivo()">Crear</button>' +
                        '</div><div class="divTableCellPetit menu"><button type="button" onclick="obtenerHistorialTecnico('+ $IdPropia +')">Tu_Historial</button></div></div>'+


                        '<div class="divTableRow"><div class="divTableCellPetit bordes"><b>Nombre:</b>' +
                        '</div><div class="divTableCellPetit bordes"><b>Tipo:</b>' +
                        '</div><div class="divTableCellPetit bordes"><b>IP:</b>' +
                        '</div><div class="divTableCellPetit bordes"><b>Ubicacion:</b>' +
                        '</div><div class="divTableCellPetit"><b>Editar</b>' +
                        '</div><div class="divTableCellPetit"><b>Historial</b></div></div></div>');
                    $.each(respuesta["datos"], function (i, v) {
                        $("#contenido .divTableBody").append('<div class="divTableRow"><div class="divTableCell bordes">' + v["nombre"] +
                            '</div><div class="divTableCellPetit bordes">' + v["tipo"] +
                            '</div><div class="divTableCellPetit bordes"><a id="enlace'+ v["id"] +'" href="http://' + v["ip"] + '" target="_blank">' + v["ip"] +
                            '</a></div><div class="divTableCell bordes">' + v["ubicacion"] +
                            '</div><div class="divTableCellPetit"><button type="button" onclick="obtenerDispositivo(' + v["id"] + ')">Editar</button>' +
                            '</div><div class="divTableCellPetit"><button type="button" onclick="obtenerHistorialDispositivo(' + v["id"] + ')">Historial</button></div></div>');
                        var start = $.now();
                        $.ajax({ type: "HEAD",//HEAD GET
                            url: "http://"+v["ip"],
                            cache:false,
                            success: function(output){
                                var ping = $.now() - start;
                                if (ping < 5000) { // useless?
                                    $("#enlace" + v["id"]).css({"color": "green"});
                                } else {
                                    $("#enlace" + v["id"]).css({"color": "orange"});
                                }
                            },
                            timeout: 5000,
                            error: function(request, status, err) {
                                if (status == "timeout") {
                                    $("#enlace" + v["id"]).css({"color": "red"});
                                } else {
                                    $("#enlace" + v["id"]).css({"color": "orangeRed"});
                                }
                            }
                        });
                    });

                    /*$.each(respuesta["datos"], function (i, v) {
                        $("#contenido").append('<div class="opcion" id="dispositivo'+v["id"]+'"><div><b>Nombre:</b> '+v["nombre"]+'&nbsp;&nbsp;&nbsp;&nbsp;<b>Tipo:</b> '+v["tipo"]+
                            ' </div><div><b>IP:</b> '+v["ip"]+'&nbsp;&nbsp;&nbsp;&nbsp;<b>Ubicacion:</b> '+v["ubicacion"]+'</div>'+
                            '<button type="button" id="'+v["id"]+'" onclick="obtenerDispositivo('+v["id"]+')">Editar</button> ' +
                            '<button type="button" id="'+v["id"]+'" onclick="obtenerHistorialDispositivo('+v["id"]+')">Historial</button></div>');
                    });*/
                });
                $("#contenedor").fadeIn(100);
            } else {
                $('#mensajes').text('Respuesta invalida del servidor');
            }
        },
        error: function () {
            $('#mensajes').text('Error carrgando lista');
        }
    });
}

function obtenerDispositivo($id) { // el detalle del dispositivo para editar
    $.ajax({
        url: $globalUrl + 'dispositivo/' + $id,
        type: 'GET',
        async: false,
        dataType: 'json',
        success: function (respuesta) {
            if (respuesta["correcta"]) {
                $("#contenedor").fadeOut(100, function () {
                    $('#tituloOpcion').text('Editando Dispositivo ' + respuesta["datos"]["id"]);
                    $("#contenido").empty();
                    $("#contenido").append('<div class="opcion" id="dispositivo"><form id="form-editar">' +
                        '<div><b>Nombre:</b> <input type="text" name="nom_dis" size="31" maxlength="50" value="' + respuesta["datos"]["nombre"] + '"></div>' +
                        '<div><b>Tipo:</b> <select name="tip_dis">' +
                        '<option value="PC">PC&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' +
                        '</option><option value="Servidor">Servidor</option><option value="Impresora">Impresora</option><option value="WIFI">WIFI</option><option value="Kiosco">Kiosco</option>' +
                        '<option value="Cámara">Cámara</option><option value="Antena">Antena</option><option value="Otro">Otro</option>' + '</select></div>' +
                        '<div><b>IP: </b><input type="text" name="ip_dis" size="38" maxlength="50" value="' + respuesta["datos"]["ip"] + '">' + '</div>' +
                        '<div><b>Ubicacion: </b> <input type="text" name="ubi_dis" size="29" maxlength="150" value="' + respuesta["datos"]["ubicacion"] + '"></div>' +
                        '<div><b>Usuario: </b><input type="text" name="user_dis" size="31" maxlength="50" value="' + respuesta["datos"]["usuario"] + '"></div>' +
                        '<div><b>Contraseña: </b> <input type="text" name="pass_dis" size="27"maxlength="50" value="' + respuesta["datos"]["PASSWORD"] + '"></div>' +
                        '<button type="button" onclick="guardarDispositivo(' + respuesta["datos"]["id"] + ')">Guardar</button>' +
                        '<button type="button" onclick="eliminarDispositivo(' + respuesta["datos"]["id"] + ')">Eliminar</button>' +
                        '<button type="button" onclick="cargarDispositivos()">Cancelar</button></form></div>');
                    $("div#dispositivo select").val(respuesta["datos"]["tipo"]);
                    if ($("div#dispositivo select").val() != respuesta["datos"]["tipo"]) {
                        $("div#dispositivo select").val("Otro");
                    }
                });
                $("#contenedor").fadeIn(100);
            } else {
                $('#mensajes').text('Respuesta invalida del servidor');
            }
        },
        error: function () {
            $('#mensajes').text('Error carrgando Dispositivo');
        }
    });
}

function nuevoDispositivo() { // el detalle del dispositivo para editar
    $("#contenedor").fadeOut(100, function () {
        $('#tituloOpcion').text('Nuevo Dispositivo');
        $("#contenido").empty();
        $("#contenido").append('<div class="opcion" id="dispositivo"><form id="form-editar">' +
            '<div><b>Nombre:</b> <input type="text" name="nom_dis" size="31" maxlength="50"></div>' +
            '<div><b>Tipo:</b> <select name="tip_dis">' +
            '<option value="PC">PC&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' +
            '</option><option value="Servidor">Servidor</option><option value="Impresora">Impresora</option><option value="WIFI">WIFI</option><option value="Kiosco">Kiosco</option>' +
            '<option value="Cámara">Cámara</option><option value="Antena">Antena</option><option value="Otro">Otro</option>' + '</select></div>' +
            '<div><b>IP: </b><input type="text" name="ip_dis" size="38" maxlength="50" </div>' +
            '<div><b>Ubicacion: </b> <input type="text" name="ubi_dis" size="29" maxlength="150"></div>' +
            '<div><b>Usuario: </b><input type="text" name="user_dis" size="31" maxlength="50"></div>' +
            '<div><b>Contraseña: </b> <input type="text" name="pass_dis" size="27"maxlength="50"></div>' +
            '<button type="button" onclick="crearDispositivo()">Guardar</button>' +
            '<button type="button" onclick="cargarDispositivos()">Cancelar</button></form></div>');
    });
    $("#contenedor").fadeIn(100);
}


function guardarDispositivo($id) { // guardara las ediciones del dispositivo
    if ($('[name="nom_dis"]').val() != "" && $('[name="nom_dis"]').val() != "null") {
        $.ajax({
            url: $globalUrl + 'dispositivo/' + $id,
            type: 'PUT',
            async: false,
            dataType: 'json',
            data: {
                nom_dis: $('[name="nom_dis"]').val(),
                ip_dis: $('[name="ip_dis"]').val(),
                user_dis: $('[name="user_dis"]').val(),
                pass_dis: $('[name="pass_dis"]').val(),
                tip_dis: $('[name="tip_dis"]').val(),
                ubi_dis: $('[name="ubi_dis"]').val()
            },
            success: setTimeout(cargarDispositivos, 200),
            error: function () {
                $('#mensajes').text('Respuesta invalida del servidor');
            }
        });
    } else {
        $('#mensajes').text('Introduce un nombre');
    }
}

function crearDispositivo() { // crea un nuevo dispositivo
    if ($('[name="nom_dis"]').val() != "" && $('[name="nom_dis"]').val() != "null") {
        $.ajax({
            url: $globalUrl + 'dispositivo/',
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {
                nom_dis: $('[name="nom_dis"]').val(),
                ip_dis: $('[name="ip_dis"]').val(),
                user_dis: $('[name="user_dis"]').val(),
                pass_dis: $('[name="pass_dis"]').val(),
                tip_dis: $('[name="tip_dis"]').val(),
                ubi_dis: $('[name="ubi_dis"]').val()
            },
            success: setTimeout(cargarDispositivos, 200),
            error: function () {
                $('#mensajes').text('Respuesta invalida del servidor');
            }
        });
    } else {
        $('#mensajes').text('Introduce un nombre');
    }
}

function eliminarDispositivo($id) {
    if (confirm('Esta seguro de la eliminacion?')) {
        $.ajax({
            url: $globalUrl + 'dispositivo/' + $id,
            type: 'DELETE',
            data: {id_dis: $id},
            success: function () {
                setTimeout(cargarDispositivos, 200);
            },
            error: function () {
                $('#mensajes').text('Respuesta invalida del servidor');
            }
        });
    }
}

function obtenerHistorialDispositivo($id) { // mostrara la lista de hisotriales de un dispositivo
    $.ajax({
        url: $globalUrl + 'historico_dispositivo/' + $id,
        type: 'GET',
        async: false,
        dataType: 'json',
        success: function (respuesta) {
            if (respuesta["correcta"]) {
                $("#contenedor").fadeOut(100, function () {
                    $('#tituloOpcion').text('Llista Historial Dispositivo ' + $id);
                    $('#mensajes').text('');
                    $("#contenido").empty();

                    $("#contenido").append('<div class="divTableBody">' +
                        '<div class="divTableRow"><div class="divTableCellPetit menu">' +
                        '</div><div class="divTableCellPetit menu"><button type="button" onclick="obtenerHistorialDispositivo($id)">Recargar</button>' +
                        '</div><div class="divTableCellPetit menu"><button type="button" onclick="nuevoHistorico('+$id+')">Crear</button>' +
                        '</div><div class="divTableCellPetit menu"><button type="button" onclick="cargarDispositivos()">Cancelar</button></div></div>'+

                        '<div class="divTableRow">' +
                        '<div class="divTableCellPetit bordes"><b>Fecha:</b>' +
                        '</div><div class="divTableCellPetit bordes"><b>Usuario:</b>' +
                        '</div><div class="divTableCellPetit bordes"><b>Descripción:</b>' +
                        '</div><div class="divTableCellPetit"><b>Obtener:</b>' +
                        '</div></div></div>');
                    $.each(respuesta["datos"], function (i, v) {
                        $("#contenido .divTableBody").append('<div class="divTableRow"><div class="divTableCellPetit bordes">' + v["fecha"] +
                            '</div><div class="divTableCellPetit bordes">' + v["usuario"] +
                            '</div><div class="divTableCell bordes">' + v["descripcion"] +
                            '</div><div class="divTableCellPetit"><button type="button" onclick="obtenerHistorico(' + v["id"] + ', true)">Editar</button></div></div>');
                    });
                });
                $("#contenedor").fadeIn(100);
            } else {
                $('#mensajes').text('Respuesta invalida del servidor');
            }
        },
        error: function () {
            $('#mensajes').text('Error carrgando Dispositivo');
        }
    });
}

function obtenerHistorico($id, $fromDis) { // detalle del historico
    $.ajax({
        url: $globalUrl + 'historico/' + $id,
        type: 'GET',
        async: false,
        dataType: 'json',
        success: function (respuesta) {
            if (respuesta["correcta"]) {
                if ($fromDis) {
                    $("#contenedor").fadeOut(100, function () {
                        $('#tituloOpcion').text('Editando historico ' + $id);
                        $("#contenido").empty();
                        $("#contenido").append('<div class="opcion"><form id="form-editar">' +
                            '<input type="hidden" name="fk_dis" size="29" maxlength="50" value="' + respuesta["datos"]["fk_dispositivo"] + '">' +
                            '<div><b>Tecnico:</b> <input type="text" name="fk_tec" size="33" maxlength="50" value="' + respuesta["datos"]["fk_tecnico"] + '"></div>' +
                            '<div><b>Fecha:</b> <input type="text" name="fec_his" size="35" maxlength="50" value="' + respuesta["datos"]["fecha"] + '"></div>' +
                            '<div><b>Descripcion: </b><textarea name="des_his"  rows="2" cols="30">'
                            + respuesta["datos"]["descripcion"] + '</textarea></div></div>' +
                            '<button type="button" onclick="guardarHistorico(' + respuesta["datos"]["id"] + ', true)">Guardar</button>' +
                            '<button type="button" onclick="eliminarHistorico(' + respuesta["datos"]["id"] +
                            ', ' + respuesta["datos"]["fk_dispositivo"] + ', true)">Eliminar</button>' +
                            '<button type="button" onclick="obtenerHistorialDispositivo(' + respuesta["datos"]["fk_dispositivo"] + ')">Cancelar</button></form></div>');
                        $('[name="fk_tec"]').autocomplete({
                            source: function (request, response) {
                                $.ajax({
                                    type: "GET",
                                    url: $globalUrl + 'nombreTecnico/' + request.term,
                                    success: response,
                                    dataType: 'json'
                                });
                            },
                            minLength: 1,
                            max: 10,
                            scroll: true,
                            delay: 500,
                            change: function (event, ui) {
                                if (ui.item == null) { //el valor es null si no coincide con ningun valor de la lista
                                    $(this).val("");
                                }
                            }
                        });
                    });
                } else {
                    $("#contenedor").fadeOut(100, function () {
                        $('#tituloOpcion').text('Editando historico ' + $id);
                        $("#contenido").empty();
                        $("#contenido").append('<div class="opcion"><form id="form-editar">' +
                            '<input type="hidden" name="fk_tec" size="33" maxlength="50" value="' + respuesta["datos"]["fk_tecnico"] + '">' +
                            '<div><b>Dispositivo:</b> <input type="text" name="fk_dis" size="29" maxlength="50" value="' + respuesta["datos"]["fk_dispositivo"] + '"></div>' +
                            '<div><b>Fecha:</b> <input type="text" name="fec_his" size="35" maxlength="50" value="' + respuesta["datos"]["fecha"] + '"></div>' +
                            '<div><b>Descripcion: </b><textarea name="des_his"  rows="2" cols="30">'
                            + respuesta["datos"]["descripcion"] + '</textarea></div></div>' +
                            '<button type="button" onclick="guardarHistorico(' + respuesta["datos"]["id"] + ',false )">Guardar</button>' +
                            '<button type="button" onclick="eliminarHistorico(' + respuesta["datos"]["id"] +
                            ', ' + respuesta["datos"]["fk_tecnico"] + ', false)">Eliminar</button>' +
                            '<button type="button" onclick="obtenerHistorialTecnico(' + respuesta["datos"]["fk_tecnico"] + ')">Cancelar</button></form></div>');
                        $('[name="fk_dis"]').autocomplete({
                            source: function (request, response) {
                                $.ajax({
                                    type: "GET",
                                    url: $globalUrl + 'nombreDispositivo/' + request.term,
                                    success: response,
                                    dataType: 'json'
                                });
                            },
                            minLength: 1,
                            max: 10,
                            scroll: true,
                            delay: 500,
                            change: function (event, ui) {
                                if (ui.item == null) { //el valor es null si no coincide con ningun valor de la lista
                                    $(this).val("");
                                }
                            }
                        });
                    });
                }
                $("#contenedor").fadeIn(100);
            } else {
                $('#mensajes').text('Respuesta invalida del servidor');
            }
        },
        error: function () {
            $('#mensajes').text('Error carrgando Dispositivo');
        }
    });
}

function nuevoHistorico($id_dispositivo) { //  formulario Historico
    $("#contenedor").fadeOut(100, function () {
        $('#tituloOpcion').text('Crear historico');
        $d = new Date();
        $mes = $d.getMonth()+1;
        $hoy = $d.getFullYear()+"-"+$mes+"-"+$d.getDate()+" "+$d.getHours()+":"+$d.getMinutes()+":"+$d.getSeconds();
        $d.getDate();
        $("#contenido").empty();
        $("#contenido").append('<div class="opcion"><form id="form-editar">' +
            '<div><b>Tecnico:</b> <input type="text" name="fk_tec" size="33" maxlength="50"></div>' +
            '<div><b>Fecha:</b> <input data-format="yyyy-MM-dd hh:mm:ss" type="text" name="fec_his" size="35" maxlength="50" value="' + $hoy + '"></div>' +
            '<div><b>Descripcion: </b><textarea name="des_his"  rows="2" cols="30"></textarea></div></div>' +
            '<button type="button" onclick="crearHistorico(' + $id_dispositivo + ')">Guardar</button>' +
            '<button type="button" onclick="obtenerHistorialDispositivo(' + $id_dispositivo + ')">Cancelar</button></form></div>');
        $('[name="fk_tec"]').autocomplete({
            source: function (request, response) {
                $.ajax({
                    type: "GET",
                    url: $globalUrl + 'nombreTecnico/' + request.term,
                    success: response,
                    dataType: 'json'
                });
            },
            minLength: 1,
            max: 10,
            scroll: true,
            delay: 500,
            change: function (event, ui) {
                if (ui.item == null) { //el valor es null si no coincide con ningun valor de la lista
                    $(this).val("");
                }
            }
        });
    });
    $("#contenedor").fadeIn(100);
}

function guardarHistorico($id, $fromDis) { // guardara las ediciones del historial
    if ($('[name="fk_dis"]').val() != "null" && $('[name="fk_dis"]').val() != "" && $('[name="fk_tec"]').val() != "null" && $('[name="fk_tec"]').val() != "") {
        $.ajax({
            url: $globalUrl + 'historico/' + $id,
            type: 'PUT',
            async: false,
            dataType: 'json',
            data: {
                id_his: $id,
                fk_dis: $('[name="fk_dis"]').val(),
                fk_tec: $('[name="fk_tec"]').val(),
                fec_his: $('[name="fec_his"]').val(),
                des_his: $('[name="des_his"]').val()
            },
            success: setTimeout(function () {
                if ($fromDis){
                    obtenerHistorialDispositivo($('[name="fk_dis"]').val());
                } else {
                    obtenerHistorialTecnico($('[name="fk_tec"]').val());
                }
            }, 200),
            error: function () {
                $('#mensajes').text('Respuesta invalida del servidor');
            }
        });
    } else {
        $('#mensajes').text('Introduce un dispositivo y tecnico');
    }
}

function crearHistorico($id_dis) { // crea un historial
    if ($id_dis != "null" && $id_dis != "" && $('[name="fk_tec"]').val() != "null" && $('[name="fk_tec"]').val() != "") {
        $.ajax({
            url: $globalUrl + 'historico/',
            type: 'POST',
            async: false,
            dataType: 'json',
            data: {
                fk_dis: $id_dis,
                fk_tec: $('[name="fk_tec"]').val(),
                fec_his: $('[name="fec_his"]').val(),
                des_his: $('[name="des_his"]').val()
            },
            success: setTimeout(function () {
                obtenerHistorialDispositivo($id_dis)
            }, 200),
            error: function () {
                $('#mensajes').text('Respuesta invalida del servidor');
            }
        });
    } else {
        $('#mensajes').text('Introduce un dispositivo y tecnico');
    }
}

function eliminarHistorico($id, $id_mas, $from_dis) {
    if (confirm('Esta seguro de la eliminacion?')) {
        $.ajax({
            url: $globalUrl + 'historico/' + $id,
            type: 'DELETE',
            data: {id_his: $id},
            success: function () {
                if ($from_dis) {
                    setTimeout(obtenerHistorialDispositivo($id_mas), 200);
                } else {
                    setTimeout(obtenerHistorialTecnico($id_mas), 200);
                }
            },
            error: function () {
                $('#mensajes').text('Respuesta invalida del servidor');
            }
        });
    }
}

function obtenerHistorialTecnico($id) { // mostrara la lista de hisotriales de un dispositivo
    $.ajax({ // No esta acabat
        url: $globalUrl + 'historico_tecnico/' + $id,
        type: 'GET',
        async: false,
        dataType: 'json',
        success: function (respuesta) {
            $("#contenedor").fadeOut(100, function () {
                if (respuesta["correcta"]) {
                    $('#tituloOpcion').text('Llista Historial Tecnico ' + $id);
                    $("#contenido").empty();
                    $('#mensajes').text('');
                    $("#contenido").append('<div class="divTableBody"><div class="divTableRow">' +
                        '<div class="divTableCellPetit bordes"><b>Dispositivo:</b>' +
                        '</div><div class="divTableCellPetit bordes"><b>Fecha:</b>' +
                        '</div><div class="divTableCellPetit bordes"><b>Descripción:</b>' +
                        '</div><div class="divTableCellPetit"><button type="button" onclick="cargarDispositivos()">Cancelar</button>' +
                        '</div></div></div>');
                    $.each(respuesta["datos"], function (i, v) {
                        $("#contenido .divTableBody").append('<div class="divTableRow"><div class="divTableCellPetit bordes">' + v["nombre"] +
                            '</div><div class="divTableCellPetit bordes">' + v["fecha"] +
                            '</div><div class="divTableCell bordes">' + v["descripcion"] +
                            '</div><div class="divTableCellPetit"><button type="button" onclick="obtenerHistorico(' + v["id"] + ', false)">Editar</button></div></div>');
                    });
                    $("#contenedor").fadeIn(100);
                } else {
                    $('#mensajes').text('Respuesta invalida del servidor');
                }
            });
        },
        error: function () {
            $('#mensajes').text('Error carrgando Dispositivo');
        }
    });
}


//Locura
