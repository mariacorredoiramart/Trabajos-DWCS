<?php
/* María Corredoira Martínez */
session_start();
require '../vendor/autoload.php'; // Carga todas las dependencias necesarias
// Importamos las clases necesarias
use Faker\Factory;
use Clases\Jugador;

$faker = Factory::create("es_ES"); // Creamos una instancia de Faker configurada para generar datos en español
$jugador = new Jugador(); //Inicializamos la clase Jugador

$randomCode = null;
while($jugador != null){
    $randomCode = $faker->ean13; // Generamos un código de barras aleatorio
    $jugador->setBarcode($randomCode);
    $comprobarCodigo = $jugador->comprobarCodigoBarras(); // Comprobamos si ese código de barras ya existe
    if(!$comprobarCodigo ){
        $jugador = null;
    }
}

$_SESSION['barcode'] = $randomCode; // Guardamos el código de barras generado en la sesión

header("Location: fcrear.php"); // Redirigimos al usuario a la página 'fcrear.php' 
exit();
