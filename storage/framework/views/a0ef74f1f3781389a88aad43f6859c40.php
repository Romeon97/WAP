<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1>Stations beheren voor abonnement: <?php echo e($subscription->identifier); ?></h1>

        <p>
            <strong>Bedrijf:</strong> <?php echo e($subscription->companyRelation->name ?? 'Onbekend'); ?><br>
            <strong>Type:</strong> <?php echo e($subscription->subscriptionType->name ?? 'Onbekend'); ?><br>
            <strong>Startdatum:</strong> <?php echo e($subscription->start_date); ?><br>
            <strong>Einddatum:</strong> <?php echo e($subscription->end_date ?? 'N.v.t.'); ?><br>
            <strong>Prijs:</strong> <?php echo e($subscription->price); ?>

        </p>

        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('manage-subscriptions.updateStations', $subscription->id)); ?>">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <label><strong>Beschikbare stations:</strong></label><br>
                <?php $__currentLoopData = $allStations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $station): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label style="display: inline-block; margin-right: 15px;">
                        <input type="checkbox" name="stations[]" value="<?php echo e($station->name); ?>"
                            <?php echo e(in_array($station->name, $linkedStations) ? 'checked' : ''); ?>>
                        <?php echo e($station->name); ?>

                    </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <button type="submit" class="btn btn-primary">Opslaan</button>
            <a href="<?php echo e(route('manage-subscriptions.index')); ?>" class="btn btn-secondary">Terug</a>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\carst\OneDrive\Documenten\GitHub\Project-WAP\resources\views/subscriptionManageStations.blade.php ENDPATH**/ ?>