<?php
require '../vendor/autoload.php';

use Jaxon\Jaxon;
use Clases\Coordenadas;
use function Jaxon\jaxon;
$jaxon = jaxon();

$jaxon->setOption('js.app.minify', false);
$jaxon->setOption('core.decode_utf8', true);
$jaxon->setOption('core.debug.on', false);
$jaxon->setOption('core.debug.verbose', false);
// URI que se encarga de procesar solicitudes
$jaxon->setOption('core.request.uri', '../src/ajax.php');

function getCoordenadas($dir)
{
    $resp = jaxon()->newResponse();
    $dir = trim($dir);
    if (strlen(string: $dir) < 4) {
        $resp->alert("Las coordenadas no son válidas");
        
        return $resp;
    }
    $c = new Coordenadas($dir);
    $lat = $c->getCoordenadas()[0];
    $lon = $c->getCoordenadas()[1];
    $alt = $c->getAltitud($lat, $lon);

    $resp->assign('lat', 'value', $lat);
    $resp->assign('lon', 'value', $lon);
    $resp->assign('alt', 'value', $alt);

    return $resp;
}

function ordenarEnvios($arrayCoordenadas, $idLista)
{
    $resp = jaxon()->newResponse();
    if (count($arrayCoordenadas) == 0) {
        $resp->alert(sMessage: "Los puntos no son válidos");
        return $resp;
    }
    $c = new Coordenadas(null);
    $datos = $c->ordenarEnvios($arrayCoordenadas, $idLista);
    $resp->call('mostrarOrdenados', $datos);
    return $resp;
}

$jaxon->register(Jaxon::CALLABLE_FUNCTION, 'getCoordenadas');
$jaxon->register(Jaxon::CALLABLE_FUNCTION, 'ordenarEnvios');
