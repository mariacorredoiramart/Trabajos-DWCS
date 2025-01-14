<?php
/* María Corredoira Martínez */

session_start();
require '../vendor/autoload.php'; // Carga todas las dependencias necesarias

use Clases\Jugador; // Importamos las clases necesarias

$nombre = null;
$apellidos = null;
$dorsal = null;
$posicion = null;
$codigoDeBarras = null;

// Comprobamos si el formulario fue enviado por el método POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recogemos los datos enviados desde el formulario
    $nombre = trim($_POST["nombre"]);
    $apellidos = trim($_POST["apellidos"]);
    $dorsal = isset($_POST["dorsal"]) && $_POST["dorsal"] != null ? trim($_POST["dorsal"]) : null;
    $posicion = isset($_POST["posicion"]) && $_POST["posicion"] != null ? trim($_POST["posicion"]) : null;
    $codigoDeBarras = trim($_POST["codigoDeBarras"]);

    if ($codigoDeBarras == null) {  // Verificamos si el código de barras fue ingresado
        $_SESSION['errorMsg'] = 'El código de barras es obligatorio.'; // Si no se ha proporcionado el código de barras, mostramos un mensaje de error
        $jugador = null;
        header("Location: fcrear.php");// Redirigimos a la página fcrear.php
        exit();
    }
}

$jugador = new Jugador(); //Inicializamos la clase Jugador
$jugador->setNombre($nombre);
$jugador->setApellidos($apellidos);
$jugador->setDorsal($dorsal);
$jugador->setPosicion($posicion);
$jugador->setBarcode($codigoDeBarras);


$resultadoDorsal = $jugador->comprobarDorsal(); // Comprobamos si el dorsal ya está asignado a otro jugador
if ($resultadoDorsal) {
    $_SESSION['errorMsg'] = 'El dorsal está asignado a otro jugador.';
    $jugador = null;
    header("Location: fcrear.php"); //Redirigimos a la página fcrear.php
    exit();
} else {   // Si el dorsal no está asignado, creamos el jugador en la base de datos
    $jugador->create();
    $jugador = null;
    header("Location: jugadores.php"); //Redirigimos a la página jugadores.php
    exit();
}


