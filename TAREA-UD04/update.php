<?php
// Autor: María Corredoira Martínez
require_once("conexion.php");

$id = null;
if (isset($_GET["id"])) {
    $id = $_GET["id"];
}

if ($id != null && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Se ha recibido un producto a través del formulario.
    $nombre = $_POST["nombre"];
    $nombre_corto = $_POST["nombreCorto"];
    $precio = $_POST["precio"];
    $descripcion = $_POST["descripcion"];
    $id_familia = $_POST["familia"];
    $query_actualizar = "UPDATE productos SET nombre_completo = '$nombre', nombre_corto = '$nombre_corto' , precio = '$precio' , descripcion = '$descripcion', familia ='$id_familia' WHERE (id = '$id');";

    // Comenzamos transacción. No se hace autocommit.
    $conProyecto->beginTransaction();
    $resultado = $conProyecto->exec($query_actualizar);

    if ($resultado) {
        $conProyecto->commit();
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=$id&actualizado=1");
    } else {
        $conProyecto->rollback();
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=$id&actualizado=0");
    }

}
// Obtenemos el producto para rellenar los 
$producto = null;
if ($id != null) {
    $registro_producto = $conProyecto->query("SELECT * FROM productos WHERE id=$id;");
    $producto = $registro_producto->fetch();
    if ($producto != null) {
        $nombre_completo = $producto["nombre_completo"];
        $nombre_corto = $producto["nombre_corto"];
        $idFamiliaProducto = $producto["familia"];
        $precio = $producto["precio"];
        $descripcion = $producto["descripcion"];

    }

    // Obtener el listado de familia de la base de datos.
    $familias_registros = $conProyecto->query("SELECT * FROM familias;");
    $listado_familias = $familias_registros->fetchAll();

}



?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Update</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>


<body>

    <div class="container mt-5">
        <h2 class="mb-4 text-center">Modificar Producto</h2>
        <?php
        if ($producto == null) {
            ?>
            <p class="text-danger mt-4">Error al obtener información del producto.</p>
            <?php
        } else {
            ?>

            <form method="POST" action="<?= $_SERVER['PHP_SELF'] . '?id=' . $id; ?>">
                <div class="row g-4">

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nombre" class="form-label"></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre"
                                value="<?= $nombre_completo ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio (€)</label>
                            <input type="number" class="form-control" id="precio" name="precio" placeholder="Precio (€)"
                                value="<?= $precio ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="5"
                                required><?= $descripcion ?> </textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nombreCorto" class="form-label">Nombre Corto</label>
                            <input type="text" class="form-control" id="nombreCorto" name="nombreCorto"
                                placeholder="Nombre corto" value="<?= $nombre_corto ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="familia" class="form-label">Familia</label>
                            <select class="form-select" id="familia" name="familia" required>

                                <?php
                                // Foreach para recorrer todos los productos
                                foreach ($listado_familias as $index => $familia) {
                                    $idFamilia = $familia["id"];
                                    $nombre = $familia["nombre"];
                                    $selected = $idFamilia == $idFamiliaProducto ? "selected" : "";
                                    ?>
                                    <option <?= $selected ?> value="<?= $idFamilia ?>"><?= $nombre ?></option>
                                    <?php
                                }
                                ?>

                            </select>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <?php
                    if ($producto != null) {
                        ?>
                        <button type="submit" class="btn btn-primary">Modificar</button>

                        <?php
                    }
                    ?>
                    <a href="listado.php" class="btn btn-info">Volver</a>
                </div>

            </form>

            <?php
            // Para mostrar si se ha actualizado el producto o no.
            if (isset($_GET["actualizado"])) {
                if ($_GET["actualizado"] == 1) {
                    ?>
                    <p class="text-success mt-4">Se ha actualizado el producto correctamente.</p>
                    <?php
                } else {
                    ?>
                    <p class="text-danger mt-4">Error al actualizar el producto.</p>
                    <?php
                }
            }
        }
        ?>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>


</html>