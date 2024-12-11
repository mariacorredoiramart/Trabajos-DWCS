<?php
// Autor: María Corredoira Martínez
require_once("conexion.php");

// Obtenemos el listado de los productos
$productos_registros = $conProyecto->query("SELECT id,nombre_completo FROM productos;");
$listado_productos = $productos_registros->fetchAll();

?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Listado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <h2 class="mb-4 text-center">Gestión de productos</h2>
        <div class="mt-4">
            <a href="crear.php" button class="btn btn-success">Crear</a>
        </div>
        <table class="table table-striped table-dark mt-4">
            <thead>
                <tr>
                    <th scope="col">Detalle</th>
                    <th scope="col">Codigo</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Foreach para recorrer todos los productos
                foreach ($listado_productos as $index => $producto) {
                    $nombre = $producto["nombre_completo"];
                    $idProducto = $producto["id"];
                    ?>
                    <tr>
                        <td>
                            <a href="detalle.php?id=<?= $idProducto ?>" class="btn btn-info">Detalle</a>
                        </td>
                        <td><?= $idProducto ?></td>
                        <td><?= $nombre ?></td>
                        <td>
                            <a href="update.php?id=<?= $idProducto ?>" class="btn btn-warning">Actualizar</a>
                            <a href="borrar.php?id=<?= $idProducto ?>" class="btn btn-danger">Borrar</a>
                        </td>
                    </tr>

                    <?php
                }
                ?>


            </tbody>
        </table>

    </div>
</body>

</html>