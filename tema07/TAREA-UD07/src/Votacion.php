<?php
// Autor: María Corredoira Martínez
namespace Clases;

class Votacion {
    public static function pintarEstrellas($idProducto){
        $voto = new Voto(); 
        $mediaVotos = $voto->obtenerMedia($idProducto);
        $nValoracion = $voto->obtenerNumVotos($idProducto);
        $estrellaEntera = intval($mediaVotos);
        $estrellaMedia = $mediaVotos - $estrellaEntera;
        $resultado = "<span>".$nValoracion." Valoraciones. </span>";
        for($i=0; $i<$estrellaEntera; $i++){
            $resultado = $resultado."<i class='fa-solid fa-star'></i>";
        }
        if($estrellaMedia >= 0.5){
            $resultado = $resultado."<i class='fa-solid fa-star-half-stroke'></i>";
        }

        $voto = null;
        return $resultado;
        
    }
    

}

