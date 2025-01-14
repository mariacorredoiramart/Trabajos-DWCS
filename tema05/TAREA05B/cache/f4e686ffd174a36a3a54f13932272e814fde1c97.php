<!-- Autor: María Corredoira Martínez -->


<?php $__env->startSection('titulo'); ?>
<?php echo e($titulo); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('encabezado'); ?>
<?php echo e($encabezado); ?>

<?php $__env->stopSection(); ?>

<?php if(isset($mensajeError)): ?>
    <div class="alert alert-danger mt-3">
        <?php echo e($mensajeError); ?>

    </div>
<?php endif; ?>

<?php $__env->startSection('contenido'); ?>
<div class="container">
        <form name="crear" method="POST" action="crearJugador.php">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="apellidos" class="form-label">Apellidos</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" placeholder="Apellidos" required>
            </div>
        </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="dorsal" class="form-label">Dorsal</label>
                    <input type="number" class="form-control" id="dorsal" name="dorsal" placeholder="Dorsal" min="1" step="1" max="99">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="posicion" class="form-label">Posición</label>
                    <select class="form-select" id="posicion" name="posicion">
                        <option value="" selected>Seleccione una posición</option>
                        <option value="Portero">Portero</option>
                        <option value="Defensa">Defensa</option>
                        <option value="Lateral Izquierdo">Lateral Izquierdo</option>
                        <option value="Lateral Derecho">Lateral derecho</option>
                        <option value="Central">Central</option>
                        <option value="Delantero">Delantero</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="codigoDeBarras" class="form-label">Código de Barras</label>
                    <input required readonly type="text" class="form-control" id="codigoDeBarras" name="codigoDeBarras" placeholder="Código de Barras" value="<?php
                    if($barcode != null){
                        echo $barcode;
                    }
                    else{
                        echo '';
                    }
                    ?>
">
                </div>
            </div>
            <div class="d-flex">
                <button type="submit" class="btn btn-primary me-3">Crear</button>
                <button type="reset" class="btn btn-success me-3">Limpiar</button>
                <a href="jugadores.php" class="btn btn-info me-3 ">Volver</a>
                <a href="generarCode.php" class="btn btn-secondary">Generar Barcode</a>
                
            </div>
        </form>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('plantillas.plantilla1', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>