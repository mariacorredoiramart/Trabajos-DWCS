<?php
// Autor: María Corredoira Martínez
namespace Clases;

class Producto extends Conexion
{

    private $id;
    private $nombre_completo;
    private $nombre_corto;
    private $precio;
    private $descripcion;

    public function __construct()
    {
        parent::__construct();
    }

    public function listarProductos()
    {
        $query = $this->conexion->prepare("SELECT * FROM productos");
        $query->execute();
        $productosRow = $query->fetchAll();
        return $productosRow;
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
     * @return  self
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get the value of precio
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set the value of precio
     *
     * @return  self
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get the value of nombre_corto
     */
    public function getNombre_corto()
    {
        return $this->nombre_corto;
    }

    /**
     * Set the value of nombre_corto
     *
     * @return  self
     */
    public function setNombre_corto($nombre_corto)
    {
        $this->nombre_corto = $nombre_corto;

        return $this;
    }

    /**
     * Get the value of nombre_completo
     */
    public function getNombre_completo()
    {
        return $this->nombre_completo;
    }

    /**
     * Set the value of nombre_completo
     *
     * @return  self
     */
    public function setNombre_completo($nombre_completo)
    {
        $this->nombre_completo = $nombre_completo;

        return $this;
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
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
?>