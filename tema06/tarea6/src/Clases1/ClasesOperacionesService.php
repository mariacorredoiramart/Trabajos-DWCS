<?php

namespace Clases\Clases1;

class ClasesOperacionesService extends \SoapClient
{

    /**
     * @var array $classmap The defined classes
     */
    private static $classmap = array (
);

    /**
     * @param array $options A array of config values
     * @param string $wsdl The wsdl file to use
     */
    public function __construct(array $options = array(), $wsdl = null)
    {
    
  foreach (self::$classmap as $key => $value) {
    if (!isset($options['classmap'][$key])) {
      $options['classmap'][$key] = $value;
    }
  }
      $options = array_merge(array (
  'features' => 1,
), $options);
      if (!$wsdl) {
        $wsdl = 'http://127.0.0.1/tarea6/servidorSoap/servicioW.php?wsdl';
      }
      parent::__construct($wsdl, $options);
    }

    /**
     * @param int $idProducto
     * @return float
     */
    public function getPVP($idProducto)
    {
      return $this->__soapCall('getPVP', array($idProducto));
    }

    /**
     * @param int $idProducto
     * @param int $idTienda
     * @return int
     */
    public function getStock($idProducto, $idTienda)
    {
      return $this->__soapCall('getStock', array($idProducto, $idTienda));
    }

    /**
     * @return array
     */
    public function getFamilias()
    {
      return $this->__soapCall('getFamilias', array());
    }

    /**
     * @param string $codFamilia
     * @return array
     */
    public function getProductosFamilia($codFamilia)
    {
      return $this->__soapCall('getProductosFamilia', array($codFamilia));
    }

}
