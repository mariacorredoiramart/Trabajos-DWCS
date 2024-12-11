<?php
// Autor: María Corredoira Martínez
require_once("conexion.php");
// Obtener el listado de familia de la base de datos.
$familias_registros = $conProyecto->query("SELECT * FROM familias;");
$listado_familias = $familias_registros->fetchAll();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Se ha recibido un producto a través del formulario.
    $nombre = $_POST["nombre"];
    $nombreCorto = $_POST["nombreCorto"];
    $precio = $_POST["precio"];
    $descripcion = $_POST["descripcion"];
    $idFamilia = $_POST["familia"];
    // Consulta para insertar un nuevo producto
    $query_guardar = "INSERT INTO productos (nombre_completo, nombre_corto, precio, descripcion, familia) VALUES ('$nombre', '$nombreCorto', '$precio', '$descripcion', '$idFamilia');";

    // Comenzamos transacción. No se hace autocommit.
    $conProyecto->beginTransaction();
    $resultado = $conProyecto->exec($query_guardar);

    if ($resultado) {
        $conProyecto->commit();
        header("Location: " . $_SERVER['PHP_SELF'] . "?creado=1");
    } else {
        $conProyecto->rollback();
        header("Location: " . $_SERVER['PHP_SELF'] . "?creado=0");
    }

}


?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>


<body>

    <div class="container mt-5">

        <h2 class="mb-4 text-center">Crear producto</h2>
        <form method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
            <div class="row g-4">

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio (€)</label>
                        <input type="number" class="form-control" id="precio" name="precio" placeholder="Precio (€)"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="5" required></textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nombreCorto" class="form-label">Nombre Corto</label>
                        <input type="text" class="form-control" id="nombreCorto" name="nombreCorto"
                            placeholder="Nombre corto" required>
                    </div>
                    <div class="mb-3">
                        <label for="familia" class="form-label">Familia</label>
                        <select class="form-select" id="familia" name="familia" required>
                            <option value="" disabled selected>Selecciona una familia</option>

                            <?php
                            // Itera sobre el listado de la familias
                            foreach ($listado_familias as $index => $familia) {
                                $idFamilia = $familia["id"];
                                $nombre = $familia["nombre"];
                                ?>
                                <option value="<?= $idFamilia ?>"><?= $nombre ?></option>
                                <?php
                            }
                            ?>

                        </select>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Crear</button>
                <button type="reset" class="btn btn-success">Limpiar</button>
                <a href="listado.php" class="btn btn-info">Volver</a>
            </div>
            <?php
            // Para mostrar si se ha creado el producto o no.
            if (isset($_GET["creado"])) {
                if ($_GET["creado"] == 1) {
                    ?>
                    <p class="text-success mt-4">Se ha creado el producto correctamente.</p>
                    <?php
                } else {
                    ?>
                    <p class="text-danger mt-4">Error al crear el producto.</p>
                    <?php
                }
            }

            ?>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>


</html>