<!-- Autor: Maria Corredoira Martinez -->
<?php
// Iniciamos la sesión o recuperamos la anterior sesión existente.
session_start();

$idioma = null;
$perfilPublico = null;
$zonaHoraria = null;
$preferenciasBorradas = false;
$preferenciasError = false;

// Comprobamos si el formulario fue enviado a través del POST.
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    if (isset($_SESSION['idioma']) && isset($_SESSION['perfilPublico']) && isset($_SESSION['zonaHoraria'])) { //Comprobamos si no hay preferencias guardadas.
        session_unset(); // Si hay preferencias guardadas las eliminamos de la sesión.
        $preferenciasBorradas = true;
    } else {
        $preferenciasError=true;
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
                <h5 class="card-title"><i class="fa-solid fa-user-gear me-2"></i>Preferencias</h5>
                <div class="mb-3">
                    <?php if ($idioma != null) {
                        ?>
                        <p>Idioma seleccionado: <?= $idioma ?></p>
                        <?php
                    } else {
                        ?>
                        <p>No hay idioma establecido.</p>
                        <?php
                    }
                    ?>
                </div>
                <div class="mb-3">
                    <?php if ($perfilPublico != null) {
                        ?>
                        <p>Perfil seleccionado: <?= $perfilPublico ?></p>
                        <?php
                    } else {
                        ?>
                        <p>No hay perfil establecido.</p>
                        <?php
                    }
                    ?>
                </div>
                <div class="mb-3">
                    <?php if ($zonaHoraria != null) {
                        ?>
                        <p>La zona horaria seleccionada: <?= $zonaHoraria ?></p>
                        <?php
                    } else {
                        ?>
                        <p>No hay zona horaria establecida.</p>
                        <?php
                    }
                    ?>
                </div>
                <div class="mt-4">
                    <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
                        <a href="preferencias.php" class="btn btn-primary">Establecer</a>
                        <button type="submit" class="btn btn-danger">Borrar</button>
                    </form>
                </div>
                <?php
                if ($preferenciasBorradas) {
                    ?>
                    <div class="mt-4">
                        <!-- Mensaje de confirmación si las preferencias han sido borradas -->
                        <p class="text-success">Las preferencias han sido borradas</p>
                    </div>
                    <?php
                }
                ?>
                 <?php
                if ($preferenciasError) {
                    ?>
                    <div class="mt-4">
                        <!-- Mensaje de error si las preferencias no han sido previamente fijadas -->
                        <p class="text-danger">Debes fijar primero las preferencias</p>
                    </div>
                    <?php
                }
                ?>


            </div>
        </div>
    </div>
</body>

</html>