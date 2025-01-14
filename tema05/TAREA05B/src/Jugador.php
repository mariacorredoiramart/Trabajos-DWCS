<?php
/* María Corredoira Martínez */

namespace Clases;

class Jugador extends Conexion
{

    private $id;
    private $nombre;
    private $apellidos;
    private $dorsal;
    private $posicion;
    private $barcode;

    public function __construct()
    {
        parent::__construct();
    }

    // Método para insertar un nuevo jugador en la base de datos
    public function create()
    {
        $query = $this->conexion->prepare('INSERT INTO jugadores (nombre, apellidos, dorsal, posicion, barcode) VALUES (:nombre, :apellidos, :dorsal, :posicion, :barcode)');
        $query->execute([':nombre' => $this->nombre, ':apellidos' => $this->apellidos, ':dorsal' => $this->dorsal, ':posicion' => $this->posicion, ':barcode' => $this->barcode]);
    }
    // Método para borrar todos los jugadores de la base de datos
    public function borrarTodo()
    {
        $query = $this->conexion->prepare('DELETE FROM jugadores');
        $query->execute();
    }
    // Método para obtener todos los jugadores de la base de datos
    public function obtenerTodo()
    {
        $query = $this->conexion->query('SELECT * FROM jugadores');
        $jugadoresRows = $query->fetchAll();
        $jugadores = [];    // Array vacío para ir complentando con clases Jugador de BD.
        foreach ($jugadoresRows as $jugadorRow) {
            $jugador = new Jugador();
            $jugador->setId($jugadorRow['id']);
            $jugador->setNombre($jugadorRow['nombre']);
            $jugador->setApellidos($jugadorRow['apellidos']);
            $jugador->setDorsal($jugadorRow['dorsal']);
            $jugador->setPosicion($jugadorRow['posicion']);
            $jugador->setBarcode($jugadorRow['barcode']);
            array_push($jugadores, $jugador);
            $jugador = null;
        }
        return $jugadores;
    }
    // Método para comprobar si existen jugadores en la base de datos
    public function compruebaJugadoresBD()
    {
        $query = $this->conexion->query("SELECT COUNT(*) as nJugadores FROM jugadores");
        $jugadoresRow = $query->fetch();    // Array de la fila encontrada
        $nJugadores = $jugadoresRow['nJugadores'];  // Accedemos a la columna nJugadores que contiene el número de filas totales.

        if ($nJugadores > 0) {
            return true;
        } else {
            return false;
        }
    }
    // Método para comprobar si un dorsal ya está asignado a otro jugador
    public function comprobarDorsal()
    {
        $query = $this->conexion->prepare("SELECT COUNT(*) as nDorsales FROM jugadores WHERE dorsal=:dorsal");
        $query->execute([':dorsal' => $this->dorsal]);
        $dorsales = $query->fetch();
        $nDorsales = $dorsales['nDorsales'];
        if ($nDorsales > 0) {
            return true;
        } else {
            return false;
        }

    }
    // Método para comprobar si un código de barras ya está asignado a otro jugador
    public function comprobarCodigoBarras()
    {
        $query = $this->conexion->prepare("SELECT COUNT(*) as nCodigo FROM jugadores WHERE barcode=:barcode");
        $query->execute([':barcode' => $this->barcode]);
        $barcodes = $query->fetch();
        $nCodigo = $barcodes['nCodigo'];
        if ($nCodigo > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Para obtener el valor del atributo id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Para obtener el valor del atributo nombre
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     *Para obtener el valor del atributo apellidos
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Para obtener el valor del atributo dorsal
     */
    public function getDorsal()
    {
        return $this->dorsal;
    }

    /**
     * Para obtener el valor del atributo posicion
     */
    public function getPosicion()
    {
        return $this->posicion;
    }

    /**
     * Para obtener el valor del atributo barcode
     */
    public function getBarcode()
    {
        return $this->barcode;
    }

    /**
     * Para establecer el valor del atributo id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Para establecer el valor del atributo nombre
     *
     * @return  self
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Para establecer el valor del atributo apellidos
     *
     * @return  self
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Para establecer el valor del atributo dorsal
     *
     * @return  self
     */
    public function setDorsal($dorsal)
    {
        $this->dorsal = $dorsal;

        return $this;
    }

    /**
     * Para establecer el valor del atributo posicion
     *
     * @return  self
     */
    public function setPosicion($posicion)
    {
        $this->posicion = $posicion;

        return $this;
    }

    /**
     * Para establecer el valor del atributo barcode
     *
     * @return  self
     */
    public function setBarcode($barcode)
    {
        $this->barcode = $barcode;

        return $this;
    }
}

