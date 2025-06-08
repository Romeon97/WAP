<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1>Query: <?php echo e($query->omschrijving); ?> (Contract: <?php echo e($contract->identifier); ?>)</h1>

        <p><strong>ID:</strong> <?php echo e($query->id); ?></p>
        <p><strong>Omschrijving:</strong> <?php echo e($query->omschrijving); ?></p>
        <p><strong>Aangemaakt op:</strong> <?php echo e($query->created_at); ?></p>

        <a href="<?php echo e(route('contracts.queries.index', $contract)); ?>" class="btn btn-secondary">
            Terug naar Queries
        </a>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\carst\OneDrive\Documenten\GitHub\Project-WAP\resources\views/queries/show.blade.php ENDPATH**/ ?>