<?php
/* María Corredoira Martínez */
session_start();
require '../vendor/autoload.php'; // Carga todas las dependencias necesarias
use Philo\Blade\Blade;// Importamos las clases necesarias

$viewsPath = ["../views"];
$cachePath = "../cache";
$blade = new Blade($viewsPath, $cachePath, null); //Inicializamos el motor de plantillas Blade, especificando las rutas para las vistas y la caché

$titulo = "Instalación";
$encabezado = "Instalación de datos";

// Genera la vista 'vjugadores' y pasa las variables necesarias
echo $blade->view()->make('vinstalacion', compact('titulo','encabezado'))->render();