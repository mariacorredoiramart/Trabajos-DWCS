<?php
/**
 * Autor: María Corredoira Martínez
 * Profesorado: Clase que hace referencia al personal profesorado.
 */
require_once "Personal.php";

class Profesorado extends Personal
{
    private $materias;
    private $cargoDirectivo;
    private static $nObjetosCreados = 0;

    public function __construct($nombre, $primerApellido, $segundoApellido, $fechaNacimiento, $direccion, $telefono, $sexo, $aniosServicio, $materias, $cargoDirectivo)
    {
        parent::__construct($nombre, $primerApellido, $segundoApellido, $fechaNacimiento, $direccion, $telefono, $sexo, $aniosServicio);
        Profesorado::$nObjetosCreados++;
        $this->materias = $materias;
        $this->cargoDirectivo = $cargoDirectivo;
    }

    /**
     * Devuelve una materia
     * @return string
     */
    public function getMaterias()
    {
        return $this->materias;
    }

    /**
     * Asigna un valor a la materia
     * @param string $materias
     * @return void
     */
    public function setMaterias($materias)
    {
        $this->materias = $materias;
    }

    /**
     * Devuelve un cargo directivo
     * @return string
     */
    public function getCargoDirectivo()
    {
        return $this->cargoDirectivo;
    }

    /**
     * Asigna un valor al cargo directivo
     * @param string $cargoDirectivo
     * @return void
     */
    public function setCargoDirectivo($cargoDirectivo)
    {
        $this->cargoDirectivo = $cargoDirectivo;
    }

    /**
     * Genera con valores al azar una clase de tipo Profesorado
     * @return Profesorado
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

        // Escoger años de servicio al azar
        $listaAniosServicio = [23, 15, 10, 5];
        $indiceAleatorio = array_rand($listaAniosServicio, 1);
        $aniosServicioEscogido = $listaAniosServicio[$indiceAleatorio];

        // Escoger materias al azar
        $listaMaterias = ["Desarrollo Web Entorno Servidor", "Sistemas Informáticos", "Programación", "Base de datos"];
        $indiceAleatorio = array_rand($listaMaterias, 1);
        $materiasEscogida = $listaMaterias[$indiceAleatorio];

        // Escoger cargo directivo al azar
        $listaCargoDirectivo = ["Ninguno", "Dirección", "Secretariado", "Jefatura estudios diurno", "Jefatura estudios personas adultas", "Vicedirección"];
        $indiceAleatorio = array_rand($listaCargoDirectivo, 1);
        $cargoDirectivoEscogido = $listaCargoDirectivo[$indiceAleatorio];


        return new Profesorado($nombreEscogido, $primerApellidoEscogido, $segundoApellidoEscogido, $fechaEscogida, $direccionEscogida, $telefonoEscogido, $sexoEscogido, $aniosServicioEscogido, $materiasEscogida, $cargoDirectivoEscogido);
    }
    /**
     * Devuelve el número de instancias de esta clase creadas
     * @return int
     */
    public static function numeroObjetosCreado()
    {
        return Profesorado::$nObjetosCreados;
    }
    /**
     * Representa la clase Profesorado como cadena de texto
     * @return string
     */
    public function __tostring()
    {
        return "Profesorado: " . parent::__tostring() . "<br>Materias: " . $this->materias . "<br>Cargo directivo: " . $this->cargoDirectivo;
    }
    /**
     * Devuelve el tipo de personal y el trabajo que realiza.
     * @return string
     */
    public function trabajar()
    {
        if ($this->getSexo() == "Hombre") {
            return "Soy un profesor" . parent::trabajar();
        } else {
            return "Soy una profesora" . parent::trabajar();
        }
    }

}
?>