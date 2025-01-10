<!-- Autor: Maria Corredoira Martinez -->
<?php
// Iniciamos la sesión o recuperamos la anterior sesión existente
session_start();

$idioma = null;
$perfilPublico = null;
$zonaHoraria = null;
$preferenciasGuardadas = false;

// Comprobamos si el formulario fue enviado a través del POST.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Guardamos las preferencias enviadas en la sesión
    $_SESSION['idioma'] = $_POST["idioma"];
    $_SESSION['perfilPublico'] = $_POST["perfilPublico"];
    $_SESSION['zonaHoraria'] = $_POST["zonaHoraria"];
    $_SESSION['preferencias'] = true;

    header(header: "Location: $_SERVER[PHP_SELF]");  // Hacemos GET a la misma página para evitar que se reenvíe el formulario si se recarga la página.
    exit();
}

if (isset($_SESSION['preferencias'])) {
    $valorPreferenciasGuardadas = $_SESSION["preferencias"];
    if ($valorPreferenciasGuardadas) {
        $preferenciasGuardadas = true;
        $_SESSION['preferencias'] = false;
    }
}

// Guardamos los valores de la sesión en las variables
if (isset($_SESSION['idioma'])) {
    $idioma = $_SESSION['idioma'];
}
if (isset($_SESSION['perfilPublico'])) {
    $perfilPublico = $_SESSION['perfilPublico'];
}
if (isset($_SESSION['zonaHoraria'])) {
    $zonaHoraria = $_SESSION['zonaHoraria'];
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="preferencias.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Preferencias usuario</h5>
                <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
                    <div class="mb-3">
                        <label for="idioma" class="form-label">Idioma</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-language icons"></i></span>
                            </div>
                            <select class="form-select form-control" name="idioma" id="idioma"
                                aria-label="Default select example" required>
                                <option value="" disabled <?= $idioma == null ? "selected" : "" ?>>Selecciona un idioma
                                </option>
                                <option <?= $idioma == "Inglés" ? "selected" : "" ?> value="Inglés">Inglés</option>
                                <option <?= $idioma == "Español" ? "selected" : "" ?> value="Español">Español</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="perfilPublico" class="form-label">Perfil público</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-users-line icons"></i></span>
                            </div>
                        <select class="form-select" name="perfilPublico" id="perfilPublico"
                            aria-label="Default select example" required>
                            <option value="" disabled selected <?= $perfilPublico == null ? "selected" : "" ?>>Selecciona
                                el tipo de perfil</option>
                            <option <?= $perfilPublico == "Sí" ? "selected" : "" ?> value="Sí">Sí</option>
                            <option <?= $perfilPublico == "No" ? "selected" : "" ?> value="No">No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="zonaHoraria" class="form-label">Zona horaria</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa-regular fa-clock icons"></i></span>
                            </div>
                        <select class="form-select" name="zonaHoraria" id="zonaHoraria"
                            aria-label="Default select example" required>
                            <option value="" disabled selected <?= $zonaHoraria == "null" ? "selected" : "" ?>>Selecciona
                                la zona horaria</option>
                            <option <?= $zonaHoraria == "GMT-2" ? "selected" : "" ?> value="GMT-2">GMT-2</option>
                            <option <?= $zonaHoraria == "GMT-1" ? "selected" : "" ?> value="GMT-1">GMT-1</option>
                            <option <?= $zonaHoraria == "GMT" ? "selected" : "" ?> value="GMT">GMT</option>
                            <option <?= $zonaHoraria == "GMT+1" ? "selected" : "" ?> value="GMT+1">GMT+1</option>
                            <option <?= $zonaHoraria == "GMT+2" ? "selected" : "" ?> value="GMT+2">GMT+2</option>
                        </select>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Establecer preferencias</button>
                        <a href="mostrar.php" class="btn btn-success">Mostrar preferencias</a>
                    </div>
                    <?php
                    if ($preferenciasGuardadas) {
                        ?>
                        <div class="mt-4">
                            <!-- Mensaje de confirmación si las preferencias han sido guardadas -->
                            <p class="text-success">Las preferencias han sido guardadas</p>
                        </div>
                        <?php
                    }
                    ?>

                </form>
            </div>
        </div>
    </div>
</body>

</html>