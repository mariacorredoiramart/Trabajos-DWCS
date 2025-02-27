<?php
// Autor: María Corredoira Martínez
include("../claves.inc.php");

// Verificar si no llega el array 'wps' por el método POST y redirigirlo a repartos.php
if (!isset($_POST['wps'])) {
    header('Location:repartos.php');
    die();
}
$arrayWaypoints = $_POST['wps'];
?>

<!DOCTYPE html>
<html>

<head>
    <title>Ruta de Reparto</title>
    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script type='text/javascript' src="../js/funciones.js"></script>
    <meta charset="utf-8" />
    <script type='text/javascript'>

        function GetMap() {
            // Creamos un mapa en el contenedor con id 'myMap' usando las credenciales de la API de Bing
            let map = new Microsoft.Maps.Map('#myMap', {
                credentials: '<?= $keyBing ?>'        // Ponemos las claves de Bing
            });
            // Se carga el módulo de direcciones de Bing Maps para poder crear rutas
            Microsoft.Maps.loadModule('Microsoft.Maps.Directions', function () {

                let directionsManager = new Microsoft.Maps.Directions.DirectionsManager(map);
                <?php
                for ($i = 0; $i < count($arrayWaypoints); $i++) {
                    //añadimos los puntos a la ruta, incluidos los del almacen
                    echo "directionsManager.addWaypoint(new Microsoft.Maps.Directions.Waypoint({ location: new Microsoft.Maps.Location($arrayWaypoints[$i]) }));\n";
                }
                ?>

                directionsManager.setRequestOptions({
                    distanceUnit: Microsoft.Maps.Directions.DistanceUnit.km,
                    routeAvoidance: [Microsoft.Maps.Directions.RouteAvoidance.avoidLimitedAccessHighway]
                });

                directionsManager.setRenderOptions({
                    drivingPolylineOptions: {
                        strokeColor: 'green',
                        strokeThickness: 6
                    },
                    waypointPushpinOptions: {
                        title: ''
                    }
                });


                directionsManager.calculateDirections();
            });
        }
    </script>
    <script type='text/javascript' src='https://www.bing.com/api/maps/mapcontrol?callback=GetMap' async defer></script>
</head>

<body style="background:#00bfa5;">
    <div class="container mt-3 ">
        <div class="d-flex justify-content-center">
            <div id="myMap" style="width:650px;height:420px;"></div>
        </div>
        <div class="d-flex justify-content-center mt-3">
            <a href='repartos.php' class='btn btn-warning'>Volver</a>
        </div>
    </div>
</body>

</html>