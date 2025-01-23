<?php
require '../vendor/autoload.php';
/* Autor: María Corredoira Martínez */
use Clases\Clases1\ClasesOperacionesService;

$operacionesService = new ClasesOperacionesService();

// Comprobamos el método getPVP
$idProducto = 1;
echo "<strong>PVP</strong><br>";
$pvp = $operacionesService->getPVP($idProducto);
if ($pvp == null) {
    echo "Producto no encontrado";
} else {
    echo "El pvp del producto es: $pvp";
}

// Comprobamos el método getStock
echo "<br><br>";
echo "<strong>STOCK</strong><br>";
$idProducto = 1;
$idTienda = 1;
$unidades = $operacionesService->getStock($idProducto, $idTienda);
$unidades = $unidades == null ? 0 : $unidades;
echo "El stock del producto es: $unidades";

// Comprobamos el método getFamilias
echo "<br><br>";
echo "<strong>CÓDIGOS DE FAMILIA</strong><br>";

$familias = $operacionesService->getFamilias();
if ($familias == null) {
    echo "No hay familias";
} else {
    foreach ($familias as $codigoFamilia) {
        echo $codigoFamilia . "<br>";
    }
}

// Comprobamos el método getProductosFamilia
echo "<br><br>";
echo "<strong>PRODUCTOS FAMILIA</strong><br>";
$codFamilia = 'ORDENA';
$productos = $operacionesService->getProductosFamilia($codFamilia);
if ($productos == null) {
    echo "No hay productos que mostrar";
} else {
    foreach($productos as $producto){
        echo $producto ."<br>";
    }
}