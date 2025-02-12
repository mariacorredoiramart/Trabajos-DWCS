<?php
// Autor: María Corredoira Martínez
require 'vendor/autoload.php';
use Clases\Usuario;
use Jaxon\Jaxon;
use Clases\Voto;
use Clases\Votacion;


use function Jaxon\jaxon;
$jaxon = jaxon();

// Opciones de configuración Jaxon: 
$jaxon->setOption('js.app.minify', false);
$jaxon->setOption('core.decode_utf8', true);
$jaxon->setOption('core.debug.on', false);
$jaxon->setOption('core.debug.verbose', false);

// URI que se encarga de procesar solicitudes
$jaxon->setOption('core.request.uri', 'ajax.php');

function vUsuario($user, $pass)
{
    $resp = jaxon()->newResponse();
    $usuario = new Usuario(); // Comprobar en base de datos si existe el usuario
    $validarUsuario = $usuario->validarUsuario($user, $pass);
    if (!$validarUsuario) {
        $resp->alert("Usuario o pass no válidos.");
    } else {
        session_start();
        $_SESSION['usu'] = $user;
        $resp->redirect(sURL: "listado.php"); // Redirigir al usuario a la página de listado
    }
    $usuario = null;
    return $resp;

}
function miVoto($idPr, $cantidad)
{
    $resp = jaxon()->newResponse();
    session_start();
    $idUs = $_SESSION['usu'];
    $voto = new Voto();
    // Intentar guardar el voto en la base de datos si no se ha votado anteriormente el producto
    $resultadoVotos = $voto->guardarVoto($idPr, $idUs, $cantidad);
    $resp->append("div_resultado","style.text-align", "center");    // Para centrar el mensaje de resultado

    if (!$resultadoVotos) {
        $resp->assign("div_resultado","innerHTML","Ya has votado el producto con código ".$idPr);
        $resp->assign("div_resultado","style.color", "red");
        //$resp->alert("Ya has votado el producto.");
    } else {
        $estrellas = Votacion::pintarEstrellas($idPr);
        $resp->assign("div_resultado","innerHTML","Has votado con éxito el producto con código ".$idPr);
        $resp->assign("div_resultado","style.color", "green");
        $resp->assign('valoracion_' . $idPr, 'innerHTML', $estrellas);
    }
    $voto = null;
    return $resp;

}

// Registrar las funciones en Jaxon para que puedan ser llamadas desde JavaScript
$jaxon->register(Jaxon::CALLABLE_FUNCTION, 'miVoto');
$jaxon->register(Jaxon::CALLABLE_FUNCTION, 'vUsuario');
?>