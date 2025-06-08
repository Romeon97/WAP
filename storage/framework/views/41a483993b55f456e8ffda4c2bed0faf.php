<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1>Contract bewerken: <?php echo e($contract->identifier); ?></h1>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('contracts.update', $contract)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="form-group">
                <label>Identifier</label>
                <input type="text" name="identifier" class="form-control"
                       value="<?php echo e(old('identifier', $contract->identifier)); ?>" required>
            </div>
            <div class="form-group">
                <label>Company ID</label>
                <input type="number" name="company_id" class="form-control"
                       value="<?php echo e(old('company_id', $contract->company_id)); ?>">
            </div>
            <div class="form-group">
                <label>Omschrijving</label>
                <input type="text" name="omschrijving" class="form-control"
                       value="<?php echo e(old('omschrijving', $contract->omschrijving)); ?>">
            </div>
            <div class="form-group">
                <label>Start Datum</label>
                <input type="date" name="start_datum" class="form-control"
                       value="<?php echo e(old('start_datum', $contract->start_datum)); ?>">
            </div>
            <div class="form-group">
                <label>Eind Datum</label>
                <input type="date" name="eind_datum" class="form-control"
                       value="<?php echo e(old('eind_datum', $contract->eind_datum)); ?>">
            </div>
            <div class="form-group">
                <label>URL</label>
                <input type="url" name="url" class="form-control"
                       value="<?php echo e(old('url', $contract->url)); ?>">
            </div>

            <button type="submit" class="btn btn-primary">Opslaan</button>
            <a href="<?php echo e(route('contracts.index')); ?>" class="btn btn-secondary">
                Annuleren
            </a>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\carst\OneDrive\Documenten\GitHub\Project-WAP\resources\views/contracts/edit.blade.php ENDPATH**/ ?>