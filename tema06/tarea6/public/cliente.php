<?php
/* Autor: María Corredoira Martínez */
require '../vendor/autoload.php'; // Carga todas las dependencias necesarias

use Clases\Operaciones;

$operaciones = new Operaciones();

// Comprobamos el método getPVP
echo "<strong>PVP</strong><br>";
$pvp = $operaciones->getPVP(1);
if ($pvp == null) {
    echo "El producto no existe";
} else {
    echo $pvp;
}
// Comprobamos el método getStock
echo "<br><br>";
echo "<strong>STOCK</strong><br>";
$unidades = $operaciones->getStock(1, 1);
if ($unidades == null) {
    echo "No hay stock";
} else {
    echo $unidades;
}

// Comprobamos el método getFamilias
echo "<br><br>";
echo "<strong>CÓDIGOS DE FAMILIA</strong><br>";
$familias = $operaciones->getFamilias();
if ($familias == null) {
    echo "No hay familias";
} else {
    foreach($familias as $codigoFamilia){
        echo $codigoFamilia ."<br>";
    }
}

// Comprobamos el método getProductosFamilia
echo "<br><br>";
echo "<strong>PRODUCTOS FAMILIA</strong><br>";
$productos = $operaciones->getProductosFamilia('ORDENA');
if ($productos == null) {
    echo "No hay productos que mostrar";
} else {
    foreach($productos as $producto){
        echo $producto ."<br>";
    }
}