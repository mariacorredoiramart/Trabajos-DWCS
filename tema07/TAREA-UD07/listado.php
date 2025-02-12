<?php
// Autor: María Corredoira Martínez
require 'defs.php';
use function Jaxon\jaxon;
use Clases\Producto;
use Clases\Votacion;

// Crear una instancia de Jaxon
$jaxon = jaxon();

session_start();

// Verificar si el usuario ha iniciadop sesión 
if (!isset($_SESSION['usu'])) {
    header('Location:login.php');
    die();
}
$usu = $_SESSION['usu'];
$productos = new Producto();
$listadoProductos = $productos->listarProductos();
$productos = null;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!--Fontawesome CDN-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title>Listado Productos</title>
</head>

<body style="background:gainsboro">
    <div class="float float-right d-inline-flex mt-2">

        <i class="fas fa-user mr-3 fa-2x"></i>
        <input type="text" size='10px' class="form-control
mr-2 bg-transparent text-info font-weight-bold" value="<?= $usu ?>" disabled>
        <a href="cerrar.php" class="btn btn-warning mr-2">Salir</a>
    </div>
    <br>
    <h4 class="container text-center mt-4 font-weight-bold">Productos onLine</h4>
    <div class="container mt-3">
        <table class="table table-striped table-dark">
            <thead>
                <tr class='text-center'>
                    <th scope="col">Código</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Valoración</th>
                    <th scope="col">Valorar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($listadoProductos as $index => $producto) {
                    $idProducto = $producto["id"];
                    $nombre = $producto["nombre_completo"];

                    ?>
                    <tr class='text-center'>
                        <th scope='row'><?= $idProducto ?></th>
                        <td><?= $nombre ?></td>
                        <td id="valoracion_<?= $idProducto ?>"> <?= Votacion::pintarEstrellas($idProducto) ?></td>
                        <td>
                            <select class="custom-select w-auto" id="cantidad_valoracion_<?= $idProducto ?>">
                                <option value="1" selected>1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                             <!-- Botón para enviar la valoración del producto -->                             
                            <button class="btn btn-md btn-primary"
                                onclick="jaxon_miVoto(<?= $idProducto ?>, jaxon.$('cantidad_valoracion_<?= $idProducto ?>').value)">Votar</button>
                        </td>
                    </tr>

                    <?php
                }
                ?>
            </tbody>
        </table>

        <div id="div_resultado"></div>
         <!-- Cargar los scripts de Jaxon necesarios -->
        <?php
        echo $jaxon->getJs();
        echo $jaxon->getScript();
        ?>

</body>

</html>