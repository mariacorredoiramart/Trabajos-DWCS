<?php
/* María Corredoira Martínez */
namespace Clases;

use PDO;
use PDOException;

class Conexion
{
    private $host;
    private $db;
    private $user;
    private $pass;
    private $dsn;
    protected $conexion;

    public function __construct()
    {
        $this->host = "localhost";
        $this->db = "practicaunidad5";
        $this->user = "gestor";
        $this->pass = "secreto";
        $this->dsn = "mysql:host={$this->host};dbname={$this->db};charset=utf8mb4";
        $this->crearConexion();
    }

    private function crearConexion()
    {
        try {
            $this->conexion = new PDO($this-> dsn, $this->user, $this->pass);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) {
            die("Error en la conexión: mensaje: " . $ex-> getMessage());
        }
    }
}