<?php
require '../vendor/autoload.php';
$url = "http://127.0.0.1/tarea6/servidorSoap/servicio.wsdl";

try {
    $server = new SoapServer(wsdl: $url);
    $server->setClass('Clases\Operaciones');
    $server->handle();
} catch (SoapFault $f) {
    die("error en server: " . $f->getMessage());
}