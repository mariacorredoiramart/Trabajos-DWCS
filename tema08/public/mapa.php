<?php
// Autor: María Corredoira Martínez
include("../claves.inc.php");

// URL de Bing Maps para cargar el mapa utilizando la clave de API
$urlBingMaps = 'https://www.bing.com/api/maps/mapcontrol?key='. $keyBing;

// Verificar si no se ha enviado un parámetro 'lat' en la URL y redirigirlo a repartos.php
if (!isset($_GET['lat']) || !isset($_GET['lon'])) {
    header('Location:repartos.php');
    die();
}


?>

<!DOCTYPE html>
<html>

<head>
    <title>Mapa</title>
    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
    integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
    <script type='text/javascript' src="<?= $urlBingMaps ?>"></script>
    <script type='text/javascript' src="../js/funciones.js"></script>
</head>
<body onload='cargarMapa();' style="background:#00bfa5;">
    <div class="container mt-3 ">
        <div class="d-flex justify-content-center">
            <div id='myMap' style='width: 650px; height: 420px;'></div>
            <div class="mt-r">

            </div>
        </div>
        <div class="d-flex justify-content-center mt-3">
            <a href='repartos.php' class='btn btn-warning'>Volver</a>
        </div>
    </div>
</body>
</html>