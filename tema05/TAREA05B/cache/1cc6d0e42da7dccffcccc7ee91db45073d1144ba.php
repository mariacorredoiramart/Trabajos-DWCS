<!-- Autor: María Corredoira Martínez -->


<?php $__env->startSection('titulo'); ?>
<?php echo e($titulo); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('encabezado'); ?>
<?php echo e($encabezado); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido'); ?>

<?php if(isset($mensajeCrearDatos)): ?>
    <div class="alert alert-success mt-3">
        <?php echo e($mensajeCrearDatos); ?>

    </div>
<?php endif; ?>

<div class="mb-4">
    <a href="fcrear.php" class="btn btn-success"><i class="fa-solid fa-plus me-2"></i>Nuevo jugador
    </a>
</div>
<div>
    <table class="table table-striped">
        <thead>
            <tr class="text-center">
                <th scope="col">Nombre Completo</th>
                <th scope="col">Posición</th>
                <th scope="col">Dorsal</th>
                <th scope="col">Código de Barras</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $jugadores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jugador): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="text-center">
                            <td><?php echo e($jugador->getApellidos() . ", " . $jugador->getNombre()); ?></td>
                            <?php if($jugador->getPosicion() != null): ?>
                                <td><?php echo e($jugador->getPosicion()); ?></td>
                            <?php else: ?>
                                <td>Posición sin asignar</td>
                            <?php endif; ?>
                            <?php if($jugador->getDorsal() != null): ?>
                                <td><?php echo e($jugador->getDorsal()); ?></td>
                            <?php else: ?>
                                <td>Dorsal sin asignar</td>
                            <?php endif; ?>
                            <td class="text-center justify-content-center d-flex">
                                <?php
                                    echo $dns1d->getBarcodeHTML($jugador->getBarcode(), 'EAN13', 2, 33, 'black');
                                ?>
                            </td>
                        </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>

    </table>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('plantillas.plantilla1', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>