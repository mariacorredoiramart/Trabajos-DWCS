<?php
/* Autor: María Corredoira Martínez */

namespace Clases;
use \PDO;

class Producto extends Conexion
{

    private $id;
    private $nombre;
    private $nombreCorto;
    private $pvp;
    private $familia;
    private $descripcion;

    public function __construct()
    {
        parent::__construct();
    }

    // Método para obtener el precio de un producto
    public function obtenerPvpProducto()
    {
        $query = $this->conexion->prepare('SELECT pvp FROM productos where id=:i');
        $query->execute([':i' => $this->id]);
        if ($query->rowCount() == 0){
            return null;
        }
        $filaProducto = $query->fetch(PDO::FETCH_OBJ);
        $pvp = $filaProducto->pvp;
        return $pvp;
    }

    // Método para obtener códigos de productos de una familia
    public function obtenerProductosFam()
    {
        $query = $this->conexion->prepare('SELECT id FROM productos where familia=:familia');
        $query->execute([':familia' => $this->familia]);
        if ($query->rowCount() == 0){
            return null;
        }
        $arrayCodProductos = [];
        $arrayProdFamilias = $query->fetchAll(PDO::FETCH_OBJ);
        foreach($arrayProdFamilias as $index => $producto){
            $arrayCodProductos[$index] = $producto->id;
         }
        return $arrayCodProductos;
    }


    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     */ 
    public function setId($id)
    {
        $this->id = $id;
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
     */ 
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * Get the value of nombreCorto
     */ 
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * Set the value of nombreCorto
     *
     */ 
    public function setNombreCorto($nombreCorto)
    {
        $this->nombreCorto = $nombreCorto;
    }

    /**
     * Get the value of pvp
     */ 
    public function getPvp()
    {
        return $this->pvp;
    }

    /**
     * Set the value of pvp
     *
     */ 
    public function setPvp($pvp)
    {
        $this->pvp = $pvp;
    }

    /**
     * Get the value of familia
     */ 
    public function getFamilia()
    {
        return $this->familia;
    }

    /**
     * Set the value of familia
     *
     */ 
    public function setFamilia($familia)
    {
        $this->familia = $familia;
    }

    /**
     * Get the value of descripcion
     */ 
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set the value of descripcion
     *
     */ 
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }
}