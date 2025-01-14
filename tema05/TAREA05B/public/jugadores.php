<?php
/* María Corredoira Martínez */
session_start();
require '../vendor/autoload.php'; // Carga todas las dependencias necesarias

// Importamos las clases necesarias
use Philo\Blade\Blade;
use Clases\Jugador;
use Milon\Barcode\DNS1D;

$viewsPath = ["../views"];
$cachePath = "../cache";
$blade = new Blade($viewsPath, $cachePath, null); //Inicializamos el motor de plantillas Blade, especificando las rutas para las vistas y la caché

//Inicializamos la clase DNS1D para generar código de barras
$dns1d = new DNS1D();
$dns1d->setStorPath($cachePath);
 
$titulo = "Jugadores";
$encabezado = "Listado de jugadores";
$jugador = new Jugador(); //Inicializamos la clase Jugador
$jugadores = $jugador->obtenerTodo();//Obtenemos todos los jugadores de la base de datos con el método obtenerTodo()
$jugador = null;
$mensajeCrearDatos = null;
if(isset($_SESSION['crearDatosMsg'])){  //Si existe un mensaje lo recuperamos de la sesión 
    $mensajeCrearDatos = $_SESSION['crearDatosMsg'];
    unset($_SESSION['crearDatosMsg']);
}

// Genera la vista 'vjugadores' y pasa las variables necesarias
echo $blade->view()->make('vjugadores', compact('titulo','encabezado', 'jugadores', 'dns1d', 'mensajeCrearDatos'))->render();