<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel="stylesheet" type="text/css" href="estilgrup.css"/>
        <style>
            #resultat{overflow:auto;width:500px;height:200px; background-color: LightGrey;
                      border:1px solid Black; padding:10px; }
            #codi{overflow:auto;width:300px;height:200px; background-color: LightGrey;
                      border:1px solid Black; padding:10px; }
            #state{overflow:auto;width:300px;height:200px; background-color: LightGrey;
                      border:1px solid Black; padding:10px; }
            #server{overflow:auto;width:500px;height:200px; background-color: LightGrey;
                      border:1px solid Black; padding:10px; }
            </style>
            <script type="text/javascript">
                var segons = 0;
                function agafaObjecte() {
                    if (window.XMLHttpRequest) {
                        return(new XMLHttpRequest());
                    } else {
                        if (window.ActiveXObject) {
                            return(new ActiveXObject("Microsoft.XMLHTTP"));
                        } else {
                            return(null);
                        }
                    }
                }
                function carrega_dades(url) {
                    var url = document.getElementById("url").value;//comentar quant trobis com fer-ho bé
                    document.getElementById('resultat').innerHTML = "";
                    document.getElementById('codi').innerHTML = "";
                    document.getElementById('state').innerHTML = "";
                    document.getElementById('server').innerHTML = "";
                    var milisegons = setInterval(cronometro, 1);
                    var dades;
                    dades = agafaObjecte();
                    dades.onreadystatechange = function () {
                        mostrar_dades(dades);
                    };
                    dades.open("GET", url, true);
                    dades.send(null);
                    clearInterval(milisegons);
                }
                function mostrar_dades(dades) {
                    switch(dades.readyState){
                        case 0:
                            document.getElementById('state').innerHTML += "<p> [ping="+ segons + "]  No iniciada (0)</p>";
                            break;
                        case 1:
                            document.getElementById('state').innerHTML += "<p> [ping="+ segons + "] Carregant (1)</p>";
                            break;
                        case 2:
                            document.getElementById('state').innerHTML += "<p> [ping="+ segons + "] Carregada (2)</p>";
                            break;
                        case 3:
                            document.getElementById('state').innerHTML += "<p> [ping="+ segons + "] Interactiva (3)</p>";
                            break;
                        case 4:
                            document.getElementById('state').innerHTML += "<p> [ping="+ segons + "] Completada (4)</p>";
                            document.getElementById('codi').innerHTML = dades.status + " " +  dades.statusText;
                            if (dades.status == 200) {
                                document.getElementById('resultat').textContent = dades.responseText;
                                document.getElementById('server').innerHTML = dades.getAllResponseHeaders();
                            }
                            break;
                    }
                }
                
                function cronometro () {
                    segons += 1;
                }
            </script>
        </head>
        <body>
            <form action="javascript:carrega_dades('index.php');" method="get"
                  enctype="text/plain">
                <input type="text" id="url" name="url" value="index.php">
                <input type="submit" value="Agafar dades">
            </form> <!-- index.php-->
            <br/>
            <div id="resultat"></div>
            <div id="codi"></div>
            <div id="state"></div>
            <div id="server"></div>
    </body>
</html>
