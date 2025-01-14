<?php
/* María Corredoira Martínez */
session_start();
require '../vendor/autoload.php'; // Carga todas las dependencias necesarias
// Importamos las clases necesarias
use Clases\Jugador;
use Faker\Factory;

$jugador = new Jugador();  //Inicializamos la clase Jugador
$jugador->borrarTodo();
$jugador = null;

$faker = Factory::create("es_ES"); // Creamos una instancia de Faker para generar datos falsos en español

$nJugadores = 10;

for ($i = 0; $i < $nJugadores; $i++) {
    $jugador = new Jugador(); //Inicializamos la clase Jugador
     // Asignamos valores aleatorios a cada campo del jugador utilizando Faker
    $jugador->setNombre($faker->firstName('male | female'));
    $jugador->setApellidos($faker->lastName . " ". $faker->lastName);
    $jugador->setDorsal($faker->unique()->numberBetween(1,99));
    $jugador->setPosicion($faker->numberBetween(1,6));
    $jugador->setBarcode($faker->unique()->ean13);
    $jugador->create();
    $jugador = null;
}

$_SESSION['crearDatosMsg'] = 'Se han creado datos con éxito'; //Almacena un mensaje en la sesión para informar que los datos se han creado correctamente

header(header: "Location: jugadores.php"); // Redirige  a la página jugadores.php

