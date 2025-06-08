<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1>Criterium Groups voor Query: <?php echo e($query->omschrijving); ?></h1>

        <a href="<?php echo e(route('contracts.queries.groups.create', [$contract, $query])); ?>"
           class="btn btn-primary mb-3">
            Nieuwe Group aanmaken
        </a>

        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Level</th>
                <th>Operator</th>
                <th>Acties</th>
            </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($grp->id); ?></td>
                    <td><?php echo e($grp->type); ?></td>
                    <td><?php echo e($grp->group_level); ?></td>
                    <td><?php echo e($grp->operator); ?></td>
                    <td>
                        <!-- Link naar de Criteria in deze groep -->
                        <a href="<?php echo e(route('contracts.queries.groups.criteria.index', [$contract, $query, $grp])); ?>"
                           class="btn btn-sm btn-info">
                            Criteria
                        </a>

                        <!-- Bewerken-knop voor deze groep -->
                        <a href="<?php echo e(route('contracts.queries.groups.edit', [$contract, $query, $grp])); ?>"
                           class="btn btn-sm btn-warning">
                            Bewerken
                        </a>

                        <!-- Verwijder-formulier -->
                        <form action="<?php echo e(route('contracts.queries.groups.destroy', [$contract, $query, $grp])); ?>"
                              method="POST"
                              style="display:inline;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Weet je zeker dat je deze group wilt verwijderen?')">
                                Verwijderen
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\carst\OneDrive\Documenten\GitHub\Project-WAP\resources\views/groups/index.blade.php ENDPATH**/ ?>