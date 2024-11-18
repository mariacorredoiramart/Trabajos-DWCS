<?php
// Autor: María Corredoira Martínez
// Fichero para controlar la conexión para mysql utilizando PDO
$host = "localhost";
$db = "proyecto";
$user = "usuario";
$pass = "user123";
$dsn = "mysql:host=$host;dbname=$db";

// Intentamos la conexión al servidor mysql
try {
    $conProyecto = new PDO($dsn, $user, $pass);
    $conProyecto->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
} catch (PDOException $e) {
    ?>
    <h2>Error al conectar a la base de datos</h2>
    <?php
    exit();
}

?>