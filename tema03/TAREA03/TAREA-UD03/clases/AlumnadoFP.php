<?php
/**
 * Autor: María Corredoira Martínez
 * AlumnadoFP: Clase que hace referencia al alumnado de fp.
 */
require_once "Alumno.php";
class AlumnadoFP extends Alumno
{
    private $cicloFormativo;
    private static $nObjetosCreados = 0;

    public function __construct($nombre, $primerApellido, $segundoApellido, $fechaNacimiento, $direccion, $telefono, $sexo, $curso, $grupo, $cicloFormativo)
    {
        parent::__construct($nombre, $primerApellido, $segundoApellido, $fechaNacimiento, $direccion, $telefono, $sexo, $curso, $grupo);
        $this->cicloFormativo = $cicloFormativo;
        AlumnadoFP::$nObjetosCreados++;
    }

    /**
     * Devuelve el ciclo formativo
     * @return string
     */
    public function getCicloFormativo()
    {
        return $this->cicloFormativo;
    }

    /**
     * Asigna el valor de ciclo formativo
     * @param string $cicloFormativo
     * @return void
     */
    public function setCicloFormativo($cicloFormativo)
    {
        $this->cicloFormativo = $cicloFormativo;
    }
    /**
     * Genera con valores al azar una clase de tipo AlumnadoFP
     * @return AlumnadoFP
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

        // Escoger ciclo formativo al azar
        $listaCicloFormativo = ["DAW", "Comercio Internacional", "Administración y finanzas", "Marketing y publicidad"];
        $indiceAleatorio = array_rand($listaCicloFormativo, 1);
        $cicloFormativoEscogido = $listaCicloFormativo[$indiceAleatorio];

        return new AlumnadoFP($nombreEscogido, $primerApellidoEscogido, $segundoApellidoEscogido, $fechaEscogida, $direccionEscogida, $telefonoEscogido, $sexoEscogido, $cursoEscogido, $grupoEscogido, $cicloFormativoEscogido);
    }
    /**
     * Devuelve el número de instancias de esta clase creadas
     * @return int
     */
    public static function numeroObjetosCreado()
    {
        return AlumnadoFP::$nObjetosCreados;
    }

    /**
     * Representa la clase AlumnadoFP como cadena de texto
     * @return string
     */
    public function __tostring()
    {
        return "Alumnado FP: " . parent::__tostring() . "<br>Ciclo formativo: " . $this->cicloFormativo;
    }

    /**
     * Devuelve el tipo de alumnado y la acción que realiza.
     * @return string
     */
    public function trabajar()
    {
        if ($this->getSexo() == "Hombre") {
            return "Soy un alumno de FP" . parent::trabajar();
        } else {
            return "Soy una alumna de FP" . parent::trabajar();
        }
    }

}
?>