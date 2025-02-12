<?php
// Autor: María Corredoira Martínez
namespace Clases;

class Voto extends Conexion
{
    private $id;
    private $cantidad;
    private $idPr;
    private $idUs;

    public function __construct()
    {
        parent::__construct();
    }

    public function guardarVoto($idPr, $idUs, $cantidad)
    {
        $query = $this->conexion->prepare("SELECT * FROM votos WHERE idPr = :idPr AND idUs = :idUs");
        $query->execute([':idPr' => $idPr, ':idUs' => $idUs]);
        if ($query->rowCount() > 0) {
            return false;
        } else {
            $consultaInsertar = $this->conexion->prepare('INSERT INTO votos (cantidad, idPr, idUs) VALUES (:cantidad, :idPr, :idUs)');
            $consultaInsertar->execute([':cantidad' => $cantidad, ':idPr' => $idPr, ':idUs' => $idUs]);
            return true;
        }

    }

    public function obtenerMedia($idPr)
    {
        $query = $this->conexion->prepare("SELECT AVG(cantidad) as media FROM votos WHERE idPr = :idPr");
        $query->execute([':idPr' => $idPr]);
        $row = $query->fetch();
        return $row['media'];
    }

    public function obtenerNumVotos($idPr)
    {
        $query = $this->conexion->prepare("SELECT COUNT(*) as nVotos FROM votos WHERE idPr = :idPr");
        $query->execute([':idPr' => $idPr]);
        $row = $query->fetch();
        return $row['nVotos'];
    }

}