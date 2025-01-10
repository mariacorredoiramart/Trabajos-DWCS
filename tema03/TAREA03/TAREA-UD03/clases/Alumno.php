<?php
/**
 * Autor: María Corredoira Martínez
 * Alumno: Clase que hace referencia al alumno.
 */
require_once "Persona.php";

abstract class Alumno extends Persona
{
    private $curso;
    private $grupo;

    public function __construct($nombre, $primerApellido, $segundoApellido, $fechaNacimiento, $direccion, $telefono, $sexo, $curso, $grupo)
    {
        parent::__construct($nombre, $primerApellido, $segundoApellido, $fechaNacimiento, $direccion, $telefono, $sexo);
        $this->curso = $curso;
        $this->grupo = $grupo;

    }

    /**
     * Devuelve el curso
     * @return string
     */
    public function getCurso()
    {
        return $this->curso;
    }

    /**
     * Asigna un valor para el curso
     * @param string $curso
     * @return void     */
    public function setCurso($curso)
    {
        $this->curso = $curso;
    }

    /**
     * Devuleve un grupo
     * @return string
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    /**
     * Asigna un valor para el grupo
     * @param string $grupo
     * @return void
     */
    public function setGrupo($grupo)
    {
        $this->grupo = $grupo;
    }

    /**
     * Representa la clase Alumno como cadena de texto
     * @return string
     */
    public function __tostring()
    {
        return parent::__tostring() . "<br>Curso: " . $this->curso . "<br>Grupo: " . $this->grupo;
    }

    /**
     * Devuelve el tipo de alumnado y la acción que realiza.
     * @return string
     */
    public function trabajar()
    {
        return " y estoy estudiando";
    }



}
?>