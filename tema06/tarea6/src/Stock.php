<?php
/* Autor: María Corredoira Martínez */

namespace Clases;
use \PDO;

class Stock extends Conexion{
    private $producto;
    private $tienda;
    private $unidades;

    public function __construct()
    {
        parent::__construct();
    }

    
    // Método para obtener el stock de un producto
    public function obtenerStockProducto()
    {
        $query = $this->conexion->prepare('SELECT unidades FROM stocks where producto=:p AND tienda=:t');
        $query->execute([':p' => $this->producto, ':t' => $this->tienda]);
        if ($query->rowCount() == 0){
            return 0;
        }
        $unidades = $query->fetch(PDO::FETCH_OBJ)->unidades;
        return $unidades;
    }

    /**
     * Get the value of unidades
     */ 
    public function getUnidades()
    {
        return $this->unidades;
    }

    /**
     * Set the value of unidades
     *
     * @return  self
     */ 
    public function setUnidades($unidades)
    {
        $this->unidades = $unidades;

        return $this;
    }

    /**
     * Get the value of tienda
     */ 
    public function getTienda()
    {
        return $this->tienda;
    }

    /**
     * Set the value of tienda
     *
     * @return  self
     */ 
    public function setTienda($tienda)
    {
        $this->tienda = $tienda;

        return $this;
    }

    /**
     * Get the value of producto
     */ 
    public function getProducto()
    {
        return $this->producto;
    }

    /**
     * Set the value of producto
     *
     * @return  self
     */ 
    public function setProducto($producto)
    {
        $this->producto = $producto;

        return $this;
    }
}
