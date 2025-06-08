<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1>Contracten</h1>

        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <!-- Knop: nieuw contract -->
        <a href="<?php echo e(route('contracts.create')); ?>" class="btn btn-primary mb-3">
            Nieuw Contract
        </a>

        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Identifier</th>
                <th>Omschrijving</th>
                <th>Acties</th>
            </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($contract->id); ?></td>
                    <td><?php echo e($contract->identifier); ?></td>
                    <td><?php echo e($contract->omschrijving); ?></td>
                    <td>
                        <!-- Naar Queries-index voor dit contract -->
                        <a href="<?php echo e(route('contracts.queries.index', $contract)); ?>"
                           class="btn btn-sm btn-info">
                            Queries
                        </a>

                        <!-- Show/Edit/Destroy (optioneel) -->
                        <a href="<?php echo e(route('contracts.show', $contract)); ?>" class="btn btn-sm btn-secondary">Details</a>
                        <a href="<?php echo e(route('contracts.edit', $contract)); ?>" class="btn btn-sm btn-warning">Bewerken</a>

                        <form method="POST"
                              action="<?php echo e(route('contracts.destroy', $contract)); ?>"
                              style="display:inline-block;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Verwijderen?')">
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\carst\OneDrive\Documenten\GitHub\Project-WAP\resources\views/contracts/index.blade.php ENDPATH**/ ?>