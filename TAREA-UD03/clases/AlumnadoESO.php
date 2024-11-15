<?php
/**
 * Autor: María Corredoira Martínez
 * AlumnadoESO Clase que hace referencia al alumnado de la ESO.
 */
require_once "Alumno.php";

class AlumnadoESO extends Alumno
{
    private static $nObjetosCreados = 0;

    public function __construct($nombre, $primerApellido, $segundoApellido, $fechaNacimiento, $direccion, $telefono, $sexo, $curso, $grupo)
    {
        parent::__construct($nombre, $primerApellido, $segundoApellido, $fechaNacimiento, $direccion, $telefono, $sexo, $curso, $grupo);
        AlumnadoESO::$nObjetosCreados++;
    }

    /**
     * Genera con valores al azar una clase de tipo AlumnadoFP
     * @return AlumnadoESO
     */
    public static function generarAlAzar()
    {
        // Escoger nombre al azar
        $listaNombres = ["María", "Juan", "Pedro", "Laura", "Carlos"];
        $indiceAleatorio = array_rand($listaNombres, 1);
        $nombreEscogido = $listaNombres[$indiceAleatorio];

        //Escoger primer apellido al azar
        $listaPrimerApellido = ["Fernández", "Martínez", "Pérez", "López"];
        $indiceAleatorio = array_rand($listaPrimerApellido, 1);
        $primerApellidoEscogido = $listaPrimerApellido[$indiceAleatorio];

        //Escoger segundo apellido al azar
        $listaSegundoApellido = ["Fernández", "Martínez", "Pérez", "López"];
        $indiceAleatorio = array_rand($listaSegundoApellido, 1);
        $segundoApellidoEscogido = $listaSegundoApellido[$indiceAleatorio];

        //Escoger fecha de nacimiento al azar
        $formatoFecha = "d-m-Y";
        $listaFecha = [date($formatoFecha, strtotime("2003/08/02")), date($formatoFecha, strtotime("2010/11/10")), date($formatoFecha, strtotime("1955/02/01")), date($formatoFecha, strtotime("1999/10/03")), date($formatoFecha, strtotime("1979/12/22"))];
        $indiceAleatorio = array_rand($listaFecha, 1);
        $fechaEscogida = $listaFecha[$indiceAleatorio];

        //Escoger primer dirección al azar
        $listaDireccion = ["Avenida Vigo", "Calle Portugal", "Calle Rodeira", "Avenida Marín"];
        $indiceAleatorio = array_rand($listaDireccion, 1);
        $direccionEscogida = $listaDireccion[$indiceAleatorio];

        //Escoger primer teléfono al azar
        $listaTelefono = ["656989787", "663622145", "656874596", "632587412"];
        $indiceAleatorio = array_rand($listaTelefono, 1);
        $telefonoEscogido = $listaTelefono[$indiceAleatorio];

        //Escoger primer sexo al azar
        $listaSexo = ["Mujer", "Hombre"];
        $indiceAleatorio = array_rand($listaSexo, 1);
        $sexoEscogido = $listaSexo[$indiceAleatorio];

        //Escoger curso al azar
        $listaCurso = ["1", "2", "3", "4"];
        $indiceAleatorio = array_rand($listaCurso, 1);
        $cursoEscogido = $listaCurso[$indiceAleatorio];

        // Escoger grupo al azar
        $listaGrupo = ["A", "B", "C", "D"];
        $indiceAleatorio = array_rand($listaGrupo, 1);
        $grupoEscogido = $listaGrupo[$indiceAleatorio];

        return new AlumnadoESO($nombreEscogido, $primerApellidoEscogido, $segundoApellidoEscogido, $fechaEscogida, $direccionEscogida, $telefonoEscogido, $sexoEscogido, $cursoEscogido, $grupoEscogido);
    }

    /**
     * Devuelve el número de instancias de esta clase creadas
     * @return int
     */
    public static function numeroObjetosCreado()
    {
        return AlumnadoESO::$nObjetosCreados;
    }

    /**
     * Representa la clase AlumnadoESO como cadena de texto
     * @return string
     */
    public function __tostring()
    {
        return "Alumnado ESO: " . parent::__tostring();
    }

    /**
     * Devuelve el tipo de alumnado y la acción que realiza.
     * @return string
     */
    public function trabajar()
    {
        if ($this->getSexo() == "Hombre") {
            return "Soy un alumno de la ESO" . parent::trabajar();
        } else {
            return "Soy una alumna de la ESO" . parent::trabajar();
        }
    }
}
?>