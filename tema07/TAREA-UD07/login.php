<?php
// Autor: María Corredoira Martínez
require 'defs.php';

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
        integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
</head>

<body style="background:#00bfa5;">
    <div class="container mt-5">
        <div class="d-flex justify-content-center h-100">
            <div class="card" style='width:24rem;'>
                <div class="card-header">
                    <h3><i class="fa fa-cog mr-1"></i>Registro</h3>
                </div>
                <div class="card-body">
                    <form name='miForm' id="miForm" method='POST'
                        onsubmit="jaxon_vUsuario(jaxon.$('usu').value, jaxon.$('pass').value);return false;">
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="usuario" id='usu' name='usu'>
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" class="form-control" placeholder="contraseña" id='pass' name='pass'>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Registrar" class="btn float-right btn-info" name='enviar'
                                id="enviar">
                        </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cargar los scripts de Jaxon necesarios -->
    <?php
    echo $jaxon->getJs();
    echo $jaxon->getScript();
    ?>

</body>

</html>