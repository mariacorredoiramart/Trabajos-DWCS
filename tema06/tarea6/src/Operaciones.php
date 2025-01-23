<?php
namespace Clases;

/* Autor: María Corredoira Martínez */

require '../vendor/autoload.php';
use Clases\Producto;
use Clases\Stock;
use Clases\Familia;

class Operaciones{
   /**
   * Obtiene el precio PVP de un producto identificado por su ID.
   * 
   * @soap
   * @param int $idProducto
   * @return float
   */
   public function getPVP(int $idProducto){
    $producto = new Producto();
    $producto->setId($idProducto);
    $pvp = $producto->obtenerPvpProducto(); 
    $producto = null;
    return $pvp;
  }

   /**
   * Obtiene el stock de un producto en una tienda
   * 
   * @soap
   * @param int $idProducto
   * @param int $idTienda
   * @return int
   */

   public function getStock(int $idProducto, int $idTienda) {
    $stock = new Stock();
    $stock->setProducto($idProducto);
    $stock->setTienda($idTienda);
    $unidades = $stock->obtenerStockProducto();
    $stock = null;
    return $unidades;
  } 

  /**
   * Obtiene el código de la familia del producto
   * 
   * @soap
   * @return array
   */
  public function getFamilias() {
    $familia = new Familia();
    $arrayCodigos = $familia->obtenerCodFamilia();
    $familia = null;
    return $arrayCodigos;
  }

   /**
   * Obtiene los productos que pertenecen a una familia
   * 
   * @soap
   * @param string $codFamilia
   * @return array
   */
  public function getProductosFamilia(string $codFamilia) {
    $producto = new Producto();
    $producto->setFamilia($codFamilia);
    $arrayCodProductos = $producto->obtenerProductosFam();
    $producto = null;
    return $arrayCodProductos;
  }

}