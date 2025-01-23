<?php
/* Autor: María Corredoira Martínez */

namespace Clases;
use \PDO;

class Familia extends Conexion{
    private $cod;
    private $nombre;


    public function __construct()
    {
        parent::__construct();
    }

     // Método para obtener el código de la familia de un producto
     public function obtenerCodFamilia()
     {
         $query = $this->conexion->prepare('SELECT cod FROM familias');
         $query->execute();
         if ($query->rowCount() == null){
             return null;
         }
        
         $arrayCodigos = [];
         $arrayFamilias = $query->fetchAll(PDO::FETCH_OBJ);

         foreach($arrayFamilias as $index => $familia){
            $arrayCodigos[$index] =  $familia->cod;
         }

        return $arrayCodigos;
    }

    /**
     * Get the value of nombre
     */ 
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */ 
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get the value of cod
     */ 
    public function getCod()
    {
        return $this->cod;
    }

    /**
     * Set the value of cod
     *
     * @return  self
     */ 
    public function setCod($cod)
    {
        $this->cod = $cod;

        return $this;
    }
}