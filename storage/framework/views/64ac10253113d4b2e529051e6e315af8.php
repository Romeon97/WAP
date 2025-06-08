<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1>Query bewerken (<?php echo e($query->omschrijving); ?>) voor contract: <?php echo e($contract->identifier); ?></h1>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('contracts.queries.update', [$contract, $query])); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="form-group">
                <label>Omschrijving</label>
                <input type="text" name="omschrijving" class="form-control"
                       value="<?php echo e(old('omschrijving', $query->omschrijving)); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Opslaan</button>
            <a href="<?php echo e(route('contracts.queries.index', $contract)); ?>" class="btn btn-secondary">
                Annuleren
            </a>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\carst\OneDrive\Documenten\GitHub\Project-WAP\resources\views/queries/edit.blade.php ENDPATH**/ ?>