<?php
// Autor: María Corredoira Martínez
require_once("conexion.php");
$producto = null;
// Para verificar si el parámetro id ha sido enviado 
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    if ($id != null) {
        // Para realizar una consulta para obtener los datos del producto según el id.
        $registro_producto = $conProyecto->query("SELECT * FROM proyecto.productos WHERE id=$id;");
        $producto = $registro_producto->fetch();

        // Si se encuentra el producto, extrae sus datos.
        if ($producto != null) {
            $nombre_completo = $producto["nombre_completo"];
            $idProducto = $producto["id"];
            $nombre_corto = $producto["nombre_corto"];
            $idFamilia = $producto["familia"];
            $precio = $producto["precio"];
            $descripcion = $producto["descripcion"];

        }

    }
}
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Detalle Producto</h2>
        <?php
        if ($producto == null) {
            ?>
            <p class="text-danger mt-4">Error al obtener detalle del producto.</p>
            <?php
        } else {
            ?>

            <div class="card bg-info text-white">
                <div class="card-header text-center">
                    <?= $nombre_completo ?>
                </div>
                <div class="card-body">
                    <p class="card-text text-center">Código: <?= $idProducto ?></p>
                    <p class="card-text">Nombre: <?= $nombre_completo ?></p>
                    <p class="card-text">Nombre corto: <?= $nombre_corto ?></p>
                    <p class="card-text">Codigo Familia: <?= $idFamilia ?></p>
                    <p class="card-text">PVP(€): <?= $precio ?></p>
                    <p class="card-text">Descripción: <?= $descripcion ?></p>
                </div>
            </div>
            <?php
        }
        ?>
        <div class="text-center mt-4">
            <a href="listado.php" class="btn btn-info text-white">Volver</a>
        </div>

    </div>

</body>

</html>