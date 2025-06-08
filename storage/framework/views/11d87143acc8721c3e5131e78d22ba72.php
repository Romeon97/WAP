<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1>Criteria voor Group #<?php echo e($group->id); ?> (Query: <?php echo e($query->omschrijving); ?>)</h1>

        <a href="<?php echo e(route('contracts.queries.groups.criteria.create', [$contract, $query, $group])); ?>"
           class="btn btn-primary mb-3">
            Nieuw Criterium
        </a>

        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Operator</th>
                <th>Value</th>
                <th>Comparison</th>
                <th>Acties</th>
            </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $criteria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $crit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($crit->id); ?></td>
                    <td><?php echo e($crit->operator); ?></td>
                    <td>
                        <?php if($crit->value_type == 1): ?>
                            <?php echo e($crit->int_value); ?>

                        <?php elseif($crit->value_type == 2): ?>
                            <?php echo e($crit->string_value); ?>

                        <?php elseif($crit->value_type == 3): ?>
                            <?php echo e($crit->float_value); ?>

                        <?php endif; ?>
                    </td>
                    <td><?php echo e($crit->comparison); ?></td>
                    <td>
                        <!-- Edit, Delete, etc. -->
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\carst\OneDrive\Documenten\GitHub\Project-WAP\resources\views/criteria/index.blade.php ENDPATH**/ ?>