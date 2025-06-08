<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1>Queries voor contract: <?php echo e($contract->identifier); ?></h1>

        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <a href="<?php echo e(route('contracts.queries.create', $contract)); ?>" class="btn btn-primary mb-3">
            Nieuwe Query toevoegen
        </a>

        <?php if($queries->isEmpty()): ?>
            <p>Geen queries gevonden voor dit contract.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Omschrijving</th>
                    <th>Aangemaakt</th>
                    <th>Acties</th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $queries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $query): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($query->id); ?></td>
                        <td><?php echo e($query->omschrijving); ?></td>
                        <td><?php echo e($query->created_at); ?></td>
                        <td>
                            <!-- Vervangen door Groups-index -->
                            <a href="<?php echo e(route('contracts.queries.groups.index', [$contract, $query])); ?>"
                               class="btn btn-sm btn-info">
                                Criterium Groups
                            </a>

                            <a href="<?php echo e(route('contracts.queries.show', [$contract, $query])); ?>"
                               class="btn btn-sm btn-secondary">
                                Details
                            </a>
                            <a href="<?php echo e(route('contracts.queries.edit', [$contract, $query])); ?>"
                               class="btn btn-sm btn-warning">
                                Bewerken
                            </a>
                            <form method="POST"
                                  action="<?php echo e(route('contracts.queries.destroy', [$contract, $query])); ?>"
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
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\carst\OneDrive\Documenten\GitHub\Project-WAP\resources\views/queries/index.blade.php ENDPATH**/ ?>