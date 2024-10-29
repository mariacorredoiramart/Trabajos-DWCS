<?php
$array_agenda = [];     // Inicializamos el array en el cual se guardarán los contactos

if (isset($_POST["nombre"])) {
    // Recibimos el nombre del formulario
    $nombre = $_POST["nombre"];
    $telefono = $_POST["telefono"];
    $json_agenda = $_POST["agenda"];    // Obtenemos el json con el contenido de los contactos
    $array_agenda = json_decode($json_agenda, true);    // Pasamos el json a array nuevamente

    if (empty($telefono)) {
        // Eliminamos el contacto
        unset($array_agenda[$nombre]);
    } else {
        // Añadimos contacto
        $array_agenda[$nombre] = $telefono;
    }
}

if(isset($_GET["limpiar"]) && $_GET["limpiar"] == 1) {
    // Limpiamos la agenda
    $array_agenda=[];
}


?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario</title>
</head>

<body style="text-align: center; background-color: rgb(223, 115, 115);">
    <h4>Agenda</h4>
    <fieldset style="width: 500px; margin: 0 auto; ">
        <legend style="text-align: left;">Datos agenda</legend>
        <?php
        if (isset($array_agenda)) {
            foreach ($array_agenda as $nombre => $telefono) {
                ?>
                <p style="text-align: left; color:blue;">
                    <span><?php echo $nombre ?></span>&nbsp;&nbsp;<span><?php echo $telefono ?></span>
                </p>
                <?php
            }
        }

        ?>

    </fieldset>
    <br>
    <fieldset style="width: 500px; margin: 0 auto; ">
        <legend style="text-align: left;">Nuevo contacto</legend>
        <form name="form1" action="agenda.php" method="POST">
            <p style="text-align: left; color:blue">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" placeholder="Nombre" id="nombre" required>
            </p>
            <p style="text-align: left; color:blue ">
                <label for="tlf">Telefono: </label>
                <input type="tel" maxlength="9" name="telefono" id="tlf" placeholder="Telefono">
            </p>
            <input type="hidden" name="agenda" value='<?php echo (json_encode($array_agenda)) ?>'>
            <div style=" margin-top: 5px;  text-align: left;">
                <input type="submit" value="Añadir contacto" style="color:blue">&nbsp;&nbsp;
                <input type="reset" value="Limpiar campos" style="color:green">
            </div>
        </form>
    </fieldset>

    <?php
    if (!empty($array_agenda)) {
        ?>
        <fieldset style="width: 500px; margin: 0 auto; text-align: left; ">
            <form name="form2" action="agenda.php" method="GET">
                <legend>Vaciar agenda</legend>
                <input type="hidden" value="1" name="limpiar" style="color:red">
                <input type="submit" value="Vaciar" style="color:red">
            </form>
        </fieldset>
        <?php
    }
    ?>



</body>

</html>