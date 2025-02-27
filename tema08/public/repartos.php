<?php
// Autor: María Corredoira Martínez
include '../src/Tools.php';
include '../src/Tasks.php';
use function Jaxon\jaxon;
use Clases\Coordenadas;

$jaxon = jaxon();
// Crear el servicio de Google Tarsks con el cliente autenticado
$service = new Google_Service_Tasks($client);

$mostrarErrorLista = null;

$listas = getListasTareas();
$arrayTareasOrdenadas = null;

$corAlmacen = Coordenadas::$coordenadasAlmacen;

// Función para obtener las listas de las tareas disponibles
function getListasTareas()
{
    global $service;
    $optParams = ['maxResults' => 100];
    $results = $service->tasklists->listTasklists($optParams);
    return $results;
}

// Función para obtener las tareas específicas de una lista
function getTareas($id)
{
    global $service;
    $res1 = $service->tasks->listTasks($id);
    return $res1;
}

// Función para comprobar si la tarea existe
function comprobarTareaExistente($tituloNuevaTarea)
{
    global $listas;
    foreach ($listas->getItems() as $lista) {
        if ($lista->getTitle() == $tituloNuevaTarea) {
            return false;
        }
    }
    return true;
}

// Verificar si se ha enviado 'lat' mediante el método POST
if (isset($_POST['lat'])) {
    $note = $_POST['lat'] . "," . $_POST['lon'];
    $title = ucwords($_POST['pro']) . ". " . ucwords($_POST['dir']) . ", ". Coordenadas::$provincia .".";
    $idLt = $_POST['idLTarea'];
    unset($_SESSION[$idLt]);
    //guardamos la tarea
    $op = ['title' => $title, 'notes' => $note];
    $tarea = new Google_Service_Tasks_Task($op);
    try {
        $res = $service->tasks->insert($idLt, $tarea);
    } catch (Google_Exception $ex) {
        die("Error al guardar la tarea: " . $ex);
    }
    unset($_POST['lat']);
}
// Si se recibe una acción por GET, se ejecuta una de las siguientes opciones
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'blt': // Borrar lista de tareas

            try {
                $service->tasklists->delete($_GET['idlt']);
            } catch (Google_Exception $ex) {
                die("Error al borrar la lista de tareas: " . $ex);
            }
            unset($_SESSION[$_GET['idlt']]);
            break;
        case 'bt': // Borrar tarea 
            try {
                $service->tasks->delete($_GET['idlt'], $_GET['idt']);
            } catch (Google_Exception $ex) {
                die("Error al borrar la tarea: " . $ex);
            }
            unset($_SESSION[$_GET['idlt']]);
            break;
        case 'nlt': // Crear nueva lista de reparto
            if (!isset($_GET['fechaReparto'])) {
                $mostrarErrorLista = "Elija una fecha.";
            } else {
                $fechaReparto = $_GET['fechaReparto'];
                $fechaActual = date("Y-m-d");
                if ($fechaReparto < $fechaActual) {
                    $mostrarErrorLista = "La fecha no puede ser inferior a la actual";
                } else {
                    $time = strtotime($fechaReparto);
                    $fechaFormateada = date('d-m-Y', $time);
                    $tituloNuevaTarea = "Repartos " . $fechaFormateada;
                    
                    $tareaExistente = comprobarTareaExistente($tituloNuevaTarea);
                    if (!$tareaExistente) {
                        $mostrarErrorLista = "La tarea ya existe.";
                    } else {
                        try {
                            $opciones = ["title" => $tituloNuevaTarea];
                            $taskList = new Google_Service_Tasks_TaskList($opciones);
                            $service->tasklists->insert($taskList);
                            $listas = getListasTareas();
                        } catch (Google_Exception $ex) {
                            die("Error al crear una lista de tareas: " . $ex);
                        }
                    }


                }
            }
            break;
        case 'oEnvios':
            global $arrayTareasOrdenadas;
            $arrayTareasOrdenadas = [];
            $arayPosicionesOrdenadas = $_GET['pos'];
            $idLista = $_GET['idLt'];
            //Obtenemos todas las tareas de esta lista de tareas
            $tareas = getTareas($idLista);
            foreach ($arayPosicionesOrdenadas as $index => $posicionOrdenada) {
                $posicion = $posicionOrdenada - 1;
                $arrayTareasOrdenadas[$index] = $tareas->getItems()[$posicion];
            }

    }
}

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
    <!-- Jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
        integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <title>Repartos</title>
    <script src="../js/funciones.js"></script>
</head>

<body style="background:#00bfa5;">
    <h4 class="text-center mt-3">Gestión de Pedidos</h4>
    <div class="container mt-4" style='width:80rem;'>
        <form action='<?php echo $_SERVER['PHP_SELF'] ?>' method='get'>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <button type='submit' class="btn btn-info"><i class='fas fa-plus mr-1'></i>Nueva Lista de Reparto
                    </button>
                </div>
                <input type='hidden' name='action' value='nlt'>
                <div class="col-md-4">
                    <input type=date class="form form-control" id="fechaReparto" name="fechaReparto"
                        placeholder='Lista de Reparto' required>
                </div>
            </div>
        </form>

        <?php
        if ($mostrarErrorLista != null) {
            ?>
            <div class="mensajeError"><?= $mostrarErrorLista ?></div>
            <?php
        }
        foreach ($listas->getItems() as $lista) {
            if ($lista->getTitle() == "My Tasks" || $lista->getTitle() == "Mis tareas" || $lista->getTitle() == "As miñas tarefas") {
                continue;
            }
            ?>
            <table class='table mt-2' id='<?= $lista->getId() ?>'>
                <thead class='bg-secondary'>
                    <tr>
                        <th scope='col' style='width:42rem;'><?= $lista->getTitle() ?></th>
                        <th scope='col' class='text-right'>
                            <a href='envio.php?id=<?= $lista->getId() ?>' class='btn btn-info mr-2 btn-sm'><i
                                    class='fas fa-plus mr-1'></i>Nuevo</a>
                            <button class='btn btn-success mr-2 btn-sm' onclick="ordenarEnvios('<?= $lista->getId() ?>')">
                                <i class='fas fa-sort mr-1'></i>Ordenar</button>
                            <a href='repartos.php?action=blt&idlt=<?= $lista->getId() ?>' class='btn btn-danger btn-sm'
                                onclick="return confirm('¿Borrar Lista?')\"><i class='fas fa-trash mr-1'></i>Borrar</a>
                        </th>
                    </tr>
                </thead>
                <tbody style='font-size:0.8rem'>
                    <?php
                    $tareas = getTareas($lista->getId());
                    foreach ($tareas->getItems() as $tarea) {
                        ?>
                        <tr>
                            <th scope='row'><?= $tarea->getTitle() . " ". $tarea->getNotes() ?>
                                <input type='hidden' class="notasCoordenadas" value='<?= $tarea->getNotes() ?>'>
                            </th>
                            <th scope='row' class='text-right'>
                                <a href='repartos.php?action=bt&idlt=<?= $lista->getId() ?>&idt=<?= $tarea->getId() ?>'
                                    class='btn btn-danger btn-sm' onclick="return confirm('¿Borrar Tarea?')"><i
                                        class='fas fa-trash mr-1'></i>Borrar</a>
                                        <?php
                                            $notas = $tarea->getNotes();
                                            $notasExplode = explode(',', $tarea->getNotes());
                                            $latitud = $notasExplode[0];
                                            $longitud = $notasExplode[1];
                                        ?>
                                <a href='mapa.php?lat=<?= $latitud ?>&lon=<?= $longitud ?>' class='btn btn-info ml-2 btn-sm'><i class='fas fa-map mr-1'></i>Mapa</a>
                            </th>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
            if ($arrayTareasOrdenadas != null) {
                ?>
                <div class='container mt-2 mb-2' style='font-size:0.8rem'>
                    <form method="POST" action="rutas.php">

                        <ul class='list-group'>
                            <?php
                            echo "<input type='hidden' name='wps[]' value=$corAlmacen>";
                            foreach ($arrayTareasOrdenadas as $index => $tareaOrdenada) {
                                ?>
                                <input type="hidden" name="wps[]" value="<?= $tareaOrdenada->getNotes() ?>">
                                <li class='list-group-item list-group-item-info'><?= ($index + 1) . ".- " . $tareaOrdenada->getTitle() ?></li>
                                <?php
                            }
                            echo "<input type='hidden' name='wps[]' value=$corAlmacen>";
                            ?>
                        </ul>
                        <br>
                        <button type='submit' class='btn btn-info btn-sm'>
                        <i class='fas fa-route mr-1'></i>Ver Ruta en Mapa</button>
                    </form>
                </div>
                <?php

            }
        }
        ?>


    </div>
</body>
<?php
echo $jaxon->getCSS();
echo $jaxon->getJs();
echo $jaxon->getScript();
?>

</html>