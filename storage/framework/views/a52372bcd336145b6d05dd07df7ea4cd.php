<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1>Contract: <?php echo e($contract->identifier); ?></h1>

        <p><strong>ID:</strong> <?php echo e($contract->id); ?></p>
        <p><strong>Omschrijving:</strong> <?php echo e($contract->omschrijving); ?></p>
        <p><strong>Start Datum:</strong> <?php echo e($contract->start_datum); ?></p>
        <p><strong>Eind Datum:</strong> <?php echo e($contract->eind_datum); ?></p>
        <p><strong>URL:</strong> <?php echo e($contract->url); ?></p>

        <a href="<?php echo e(route('contracts.index')); ?>" class="btn btn-secondary">Terug naar lijst</a>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\carst\OneDrive\Documenten\GitHub\Project-WAP\resources\views/contracts/show.blade.php ENDPATH**/ ?>