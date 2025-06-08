<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1>CriteriumGroup bewerken voor query: <?php echo e($query->omschrijving); ?></h1>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Pas route en velden aan naar je eigen structuur -->
        <form action="<?php echo e(route('contracts.queries.groups.update', [$contract, $query, $group])); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <!-- Voorbeeldvelden -->
            <div class="form-group">
                <label>Type</label>
                <input type="number" name="type" class="form-control"
                       value="<?php echo e(old('type', $group->type)); ?>">
            </div>
            <div class="form-group">
                <label>Group Level</label>
                <input type="number" name="group_level" class="form-control"
                       value="<?php echo e(old('group_level', $group->group_level)); ?>">
            </div>
            <div class="form-group">
                <label>Operator</label>
                <input type="number" name="operator" class="form-control"
                       value="<?php echo e(old('operator', $group->operator)); ?>">
            </div>

            <button type="submit" class="btn btn-primary">Opslaan</button>
            <a href="<?php echo e(route('contracts.queries.groups.index', [$contract, $query])); ?>"
               class="btn btn-secondary">
                Annuleren
            </a>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\carst\OneDrive\Documenten\GitHub\Project-WAP\resources\views/groups/edit.blade.php ENDPATH**/ ?>