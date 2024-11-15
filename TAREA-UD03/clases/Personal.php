<?php

require_once "Persona.php";

abstract class Personal extends Persona
{
    private $aniosServicio;

    public function __construct($nombre, $primerApellido, $segundoApellido, $fechaNacimiento, $direccion, $telefono, $sexo, $aniosServicio)
    {
        parent::__construct($nombre, $primerApellido, $segundoApellido, $fechaNacimiento, $direccion, $telefono, $sexo);
        $this->aniosServicio = $aniosServicio;
    }

    /**
     * Devuelve los años de servicio
     * @return mixed
     */
    public function getAniosServicio()
    {
        return $this->aniosServicio;
    }

    /**
     * Asigna un valor para los años de servicio
     * @param int $aniosServicio
     * @return void
     */
    public function setAniosServicio($aniosServicio)
    {
        $this->aniosServicio = $aniosServicio;
    }

    /**
     * Representa la clase Conserje como cadena de texto
     * @return string
     */

    public function __tostring()
    {
        return parent::__tostring() . "<br>Años de servicio: " . $this->aniosServicio;
    }

    /**
     * Devuelve el tipo de personal y el trabajo que realiza.
     * @return string
     */
    public function trabajar()
    {
        return " y estoy trabajando";
    }

}

?>