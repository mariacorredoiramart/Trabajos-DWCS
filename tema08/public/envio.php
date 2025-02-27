<?php
// Autor: María Corredoira Martínez
include '../src/Tools.php';
use Clases\Producto;

$listaProductos = new Producto;
$stmt = $listaProductos->listarProductos(); // Obtener la lista de productos desde la base de datos

// Verificar si no se ha enviado un parámetro 'id' en la URL y redirigirlo a repartos.php
if (!isset($_GET['id'])) {
    header('Location:repartos.php');
    die();
}
$id = $_GET['id'];
use function Jaxon\jaxon;

$jaxon = jaxon();

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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
        integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <title>Apartado 4-3</title>
    <script type="text/javascript" src="../js/funciones.js"></script>
</head>

<body style="background:#00bfa5;">
    <div class="container mt-3">
        <div class="d-flex justify-content-center h-100">
            <div class="card" style='width:28rem;'>
                <div class="card-header">
                    <h3><i class="fas fa-cart-plus mr-2"></i>Crear Envio</h3>
                </div>
                <div class="card-body">
                    <form name="f1" method='POST' action='repartos.php'>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="width:2.5rem;"><i class="fas fa-city"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Dirección" id='dir' name='dir'
                                required>

                        </div>
                        <div class="form-group mt-1">
                            <button class="btn btn-info mr-2" id="vDireccion" onclick="getCoordenadas(event);">Ver
                                Coordenadas
                            </button>
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Latitud" id='lat' required name='lat'
                                readonly>

                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="longitud" id='lon' name='lon' required
                                readonly>
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Altitud" id='alt' required name='alt'
                                readonly>

                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-box-open"></i></span>
                            </div>
                            <select name="pro" id="pro" class='form-control'>
                                <option value="" disabled selected>Elige un producto</option>
                                <?php foreach ($stmt as $index => $producto) { // Mostrar los productos disponibles en el select
                                    $nombreProducto = $producto["nombre"];
                                    ?>
                                    <option value='<?= $nombreProducto; ?>'><?= $nombreProducto; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="idLTarea" value="<?php echo $id; ?>">
                            <input type='submit' class="btn btn-info mr-2" id="vDireccion" value="Nuevo Envio">
                            <a href="repartos.php" class="btn btn-success">Volver</a>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
<?php
echo $jaxon->getCSS();
echo $jaxon->getJs();
echo $jaxon->getScript();
?>

</html>