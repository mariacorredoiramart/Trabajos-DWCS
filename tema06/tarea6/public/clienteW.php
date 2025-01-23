<?php
$url = 'http://127.0.0.1/tarea6/servidorSoap/servicio.wsdl';
try {
    $cliente = new SoapClient($url);
} catch (SoapFault $ex) {
    die("Error en el cliente: " . $ex->getMessage());
}
//Vemos las funciones que nos ofrece el servicio
//var_dump($cliente->__getFunctions());
//echo "<br>";


// Comprobamos el método getPVP
$idProducto = 1;
echo "<strong>PVP</strong><br>";
$pvp = $cliente->__soapCall('getPvp', ['id' => $idProducto]);
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
$unidades = $cliente->__soapCall('getStock', ['idProducto' => $idProducto, 'idTienda' => $idTienda]);
$unidades = $unidades == null ? 0 : $unidades;
echo "El stock del producto es: $unidades";

// Comprobamos el método getFamilias
echo "<br><br>";
echo "<strong>CÓDIGOS DE FAMILIA</strong><br>";

$familias = $cliente->__soapCall('getFamilias', []);
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
$productos = $cliente->__soapCall('getProductosFamilia',['codFamilia' => $codFamilia]);
if ($productos == null) {
    echo "No hay productos que mostrar";
} else {
    foreach($productos as $producto){
        echo $producto ."<br>";
    }
}