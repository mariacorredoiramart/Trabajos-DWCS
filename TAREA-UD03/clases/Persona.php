<?php
abstract class Persona
{
    private $nombre;
    private $primerApellido;
    private $segundoApellido;
    private $fechaNacimiento;
    private $direccion;
    private $telefono;
    private $sexo;

    public function __construct($nombre, $primerApellido, $segundoApellido, $fechaNacimiento, $direccion, $telefono, $sexo)
    {
        $this->nombre = $nombre;
        $this->primerApellido = $primerApellido;
        $this->segundoApellido = $segundoApellido;
        $this->fechaNacimiento = $fechaNacimiento;
        $this->direccion = $direccion;
        $this->telefono = $telefono;
        $this->sexo = $sexo;
    }

    /**
     * Devuelve el nombre
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }


    /**
     * Asigna el valor al nombre
     * @param string $nombre
     * @return void
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * Devuelve el primer apellido
     * @return string
     */
    public function getPrimerApellido()
    {
        return $this->primerApellido;
    }

    /**
     * Asigna un valor al primer apellido
     * @param string $primerApellido
     * @return void
     */
    public function setPrimerApellido($primerApellido)
    {
        $this->primerApellido = $primerApellido;
    }

    /**
     * Devuelve el segundo apellido
     * @return string
     */
    public function getSegundoApellido()
    {
        return $this->segundoApellido;
    }

    /**
     * Asigna el valor para el segundo apellido
     * @param string $segundoApellido
     * @return void
     */
    public function setSegundoApellido($segundoApellido)
    {
        $this->segundoApellido = $segundoApellido;
    }

    /**
     * Devuelve la fecha de nacimiento
     * @return string
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * Asigna un valor para la fecha de nacimiento
     * @param string $fechaNacimiento
     * @return void
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;
    }

    /**
     * Devuelve una dirección
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Asigna un valor para la dirección
     * @param string $direccion
     * @return void
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }

    /**
     * Devuelve un teléfono
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Asigna un valor para teléfono
     * @param string $telefono
     * @return void
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    /**
     * Devuelve un sexo
     * @return string
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Asigna un valor para el sexo
     * @param string $sexo
     * @return void
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;
    }

    /**
     * Método abstracto para generar una clase con valores aleatorios
     * @return void
     */
    public abstract static function generarAlAzar();

    /**
     * Método abstracto para devolver un número de instancias creadas
     * @return int
     */
    public abstract static function numeroObjetosCreado();

    /**
     * Método abstracto que devuelve el tipo de acción que se realiza
     * @return string
     */
    public abstract function trabajar();

    /**
     * Representa la clase Persona como cadena de texto
     * @return string
     */
    public function __tostring()
    {
        return "<br>Nombre: " . $this->nombre . "<br>Primer apellido: " . $this->primerApellido . "<br>Segundo apellido: " .
            $this->segundoApellido . "<br>Fecha nacimiento: " . $this->fechaNacimiento . "<br>Dirección: " . $this->direccion .
            "<br>Teléfono: " . $this->telefono . "<br>Sexo: " . $this->sexo;
    }

}

?>