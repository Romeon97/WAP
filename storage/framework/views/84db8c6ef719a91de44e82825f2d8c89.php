<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1 class="page-title">🌍 Weather Stations</h1>

        <div class="station-grid">
            <?php $__currentLoopData = $stations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $station): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('station.measurements', $station->station_name)); ?>" class="station-card">
                    <h3><?php echo e($station->station_name); ?></h3>
                    <p>Country: <?php echo e($station->country_code ?? 'Unknown'); ?></p>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\carst\OneDrive\Documenten\GitHub\Project-WAP\resources\views/nearestlocationView.blade.php ENDPATH**/ ?>