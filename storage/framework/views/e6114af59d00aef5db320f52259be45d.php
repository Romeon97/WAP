<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1 class="page-title">Endpoint Logs</h1>

        <!-- Filter formulier om te zoeken op een specifiek endpoint -->
        <form method="GET" action="<?php echo e(route('logs.index')); ?>" style="margin-bottom: 20px;">
            <label for="endpoint">Filter endpoint:</label>
            <select name="endpoint" id="endpoint">
                <option value="">Alle endpoints</option>
                <?php $__currentLoopData = $endpoints; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $endpoint): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($endpoint->endpoint_used); ?>"
                        <?php echo e((isset($selectedEndpoint) && $selectedEndpoint == $endpoint->endpoint_used) ? 'selected' : ''); ?>>
                        <?php echo e($endpoint->endpoint_used); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="submit" class="map-btn">Filter</button>
        </form>

        <!-- Tabel met loggegevens -->
        <table class="user-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Identifier</th>
                <th>Endpoint Used</th>
                <th>Files Downloaded</th>
                <th>Activity Date</th>
                <th>Activity Time</th>
                <th>Authorized</th>
                <th>Data Transferred</th>
            </tr>
            </thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($log->id); ?></td>
                    <td><?php echo e($log->identifier); ?></td>
                    <td><?php echo e($log->endpoint_used); ?></td>
                    <td><?php echo e($log->files_downloaded); ?></td>
                    <td><?php echo e($log->activity_date); ?></td>
                    <td><?php echo e($log->activity_time); ?></td>
                    <td><?php echo e($log->authorized); ?></td>
                    <td><?php echo e($log->data_transferred); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8">Geen loggegevens gevonden.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\carst\OneDrive\Documenten\GitHub\Project-WAP\resources\views/LogView.blade.php ENDPATH**/ ?>