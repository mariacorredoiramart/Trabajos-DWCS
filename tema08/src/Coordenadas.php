<?php
// Autor: María Corredoira Martínez
namespace Clases;

class Coordenadas
{
    public static $provincia = "Pontevedra";

    public static $iniciourl = "http://dev.virtualearth.net/REST/v1/Locations/ES/";
    public static $finurl = "?include=ciso2&maxResults=1&c=es";
    public static $coordenadasAlmacen = "42.4178768,-8.6390388";

    public $coordenadas;
    public $url;
    public $keyBing;

    public function __construct($direccion)
    {
        include("../claves.inc.php");
        $this->keyBing = $keyBing;
        if ($direccion != null) {
            $dir = str_replace(" ", "%20", $direccion);
            $this->url = self::$iniciourl . self::$provincia . "/$dir" . self::$finurl . "&key=$this->keyBing";
        }
    }


    public function getCoordenadas()
    {
        $salida = file_get_contents($this->url);
        $salida1 = json_decode($salida, true);
        $resultado = $salida1["resourceSets"][0]["resources"][0]["point"]["coordinates"];
        return $resultado;
    }

    public function getAltitud($lat, $lon)
    {
        $urlAltitud = "http://dev.virtualearth.net/REST/v1/Elevation/List?points=$lat,$lon&key=" . $this->keyBing;
        $salida = file_get_contents($urlAltitud);
        $resultado = json_decode($salida, true);
        return $resultado["resourceSets"][0]["resources"][0]["elevations"][0];
    }

    public function ordenarEnvios($arrayCoordenadas, $idLista)
    {
        //Ponemos las coordenadas del alamacén por ejemplo '42.41787688102116, -8.639038826782311' como inicio y fin de la ruta
        $base = "http://dev.virtualearth.net/REST/v1/Routes/driving?c=es&wayPoint.0=".self::$coordenadasAlmacen."&";
        $num = 1;
        $trozo = "";
        for ($i = 0; $i < count($arrayCoordenadas); $i++) {
            $trozo .= "wayPoint." . $num . "=" . $arrayCoordenadas[$i] . "&";
            $num++;
        }
        $trozo = $trozo . "wayPoint." . $num . "= ". self::$coordenadasAlmacen."&optimize=distance&optWp=true&routeAttributes=routePath&key=$this->keyBing";
        $url = $base . $trozo;
        $salida = file_get_contents($url);
        $salida1 = json_decode($salida, true);
        $wayp = $salida1["resourceSets"][0]["resources"][0]['waypointsOrder'];
        // Quitamos el primero y el uúltimo (inicio y fin) (El almacén)
        array_shift($wayp);
        array_pop($wayp);

        $resp = [];
        $resp["idLista"] = $idLista;
        for ($i = 0; $i < count($wayp); $i++) {
            $resp["posiciones"][$i] = substr(strstr($wayp[$i], '.'), 1);   // Eliminamos la parte de wp. y nos quedamos con el número optimizado del orden de reparto.
        }
        return $resp;   // Array con la ruta ya ordenada
    }
}

