<?php
// Autor: María Corredoira Martínez
require_once("conexion.php");

// Para verificar si el parámetro id ha sido enviado 
$id = null;
if (isset($_GET["id"])) {
    $id = $_GET["id"];
}

$producto = null;
if ($id != null) {
    // Para realizar una consulta para obtener los datos del producto según el id.
    $registro_producto = $conProyecto->query("SELECT * FROM productos WHERE id=$id;");
    $producto = $registro_producto->fetch();
}

if ($producto != null) {
    // Consulta para borrar el producto
    $query_borrar = "DELETE FROM productos WHERE (id = '$id');";

    // Comenzamos transacción. No se hace autocommit.
    $conProyecto->beginTransaction();
    $resultado = $conProyecto->exec($query_borrar);

    if ($resultado) {
        $conProyecto->commit();
        $borrado = true;
    } else {
        $conProyecto->rollback();
        $borrado = false;
    }
}

?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Borrar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <?php
        // Si el producto es null, se muestra un mensaje de error.
        if ($producto == null) {
            ?>
            <p class="text-danger mt-4">Error al obtener información del producto.</p>
            <?php
        } else {
            ?>
            <?php
            //Si el producto fue encontrado se muestra un mensaje u otro dependiendo del resultado.
            if ($borrado) {
                ?>
                <p class="text-success mt-4 d-inline-block">Producto de Código: <?= $id ?> Borrado correctamente.</p>
                <?php
            } else {
                ?>
                <p class="text-danger mt-4  d-inline-block">Error al borrar el producto con Código: <?= $id ?>.</p>
                <?php
            }
        }
        ?>
        <a href="listado.php" class="btn btn-outline-secondary">Volver</a>

    </div>

</body>


</html>