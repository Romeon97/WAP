<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1>Nieuwe CriteriumGroup aanmaken voor query: <?php echo e($query->omschrijving); ?></h1>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Let op: pas de route aan als je structureel andere naming hebt -->
        <form action="<?php echo e(route('contracts.queries.groups.store', [$contract, $query])); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <!-- Voorbeeldvelden (pas aan aan jouw DB-kolommen) -->
            <div class="form-group">
                <label>Type</label>
                <input type="number" name="type" class="form-control" value="<?php echo e(old('type')); ?>" required>
            </div>
            <div class="form-group">
                <label>Group Level</label>
                <input type="number" name="group_level" class="form-control" value="<?php echo e(old('group_level')); ?>" required>
            </div>
            <div class="form-group">
                <label>Operator</label>
                <input type="number" name="operator" class="form-control" value="<?php echo e(old('operator')); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Opslaan</button>
            <a href="<?php echo e(route('contracts.queries.groups.index', [$contract, $query])); ?>"
               class="btn btn-secondary">
                Annuleren
            </a>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\carst\OneDrive\Documenten\GitHub\Project-WAP\resources\views/groups/create.blade.php ENDPATH**/ ?>