<?php
/* María Corredoira Martínez */
session_start();
require '../vendor/autoload.php'; // Carga todas las dependencias necesarias
use Philo\Blade\Blade;// Importamos las clases necesarias

$viewsPath = ["../views"];
$cachePath = "../cache";
$blade = new Blade($viewsPath, $cachePath, null);  //Inicializamos el motor de plantillas Blade, especificando las rutas para las vistas y la caché


$titulo = "Crear";
$encabezado = "Crear Jugador";
$mensajeError = null;
$barcode = null;

// Comprobamos si hay un mensaje de error en la sesión y lo recuperamos
if(isset($_SESSION['errorMsg'])){
    $mensajeError = $_SESSION['errorMsg'];
    unset($_SESSION['errorMsg']);
}
// Comprobamos si hay un código de barras almacenado en la sesión
if(isset($_SESSION['barcode'])){
    $barcode = $_SESSION['barcode'];
    unset($_SESSION['barcode']);
}

// Genera la vista 'vcrear' y pasa las variables necesarias
echo $blade->view()->make('vcrear', compact('titulo','encabezado', 'mensajeError', 'barcode'))->render();
