<?php
// Autor: María Corredoira Martínez
namespace Clases;

class Usuario extends Conexion
{
    private $usuario;
    private $pass;

    public function __construct()
    {
        parent::__construct();
    }

    public function validarUsuario($user, $pass)
    {
        $pass1 = hash('sha256', $pass);
        $query = $this->conexion->prepare("SELECT * FROM usuarios WHERE usuario = :user AND pass = :pass");
        $query->execute([':user' => $user, ':pass' => $pass1]);
        if ($query->rowCount() == 0) {
            return false;
        }
        return true;
    }

}

?>