<?php
/* María Corredoira Martínez */

require '../vendor/autoload.php'; // Carga todas las dependencias necesarias

use Clases\Jugador; // Importamos las clases necesarias

$jugador = new Jugador();  //Inicializamos la clase Jugador

if ($jugador->compruebaJugadoresBD()) {  //Si existen jugadores en la base de datos
    $jugador = null;
    header(header: "Location: jugadores.php"); // Redirige  a la página jugadores.php si hay jugadores en la base de datos
    exit();
} else {
    $jugador = null;
    header(header: "Location: instalacion.php"); // Redirige  a la página instalacion.php si no hay jugadores en la base de datos
    exit();
}
