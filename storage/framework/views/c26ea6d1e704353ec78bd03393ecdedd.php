<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1>Abonnement bewerken</h1>

        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('manage-subscriptions.update', $subscription->id)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="input-group">
                <label>Company:</label>
                <input type="number" name="company" value="<?php echo e(old('company', $subscription->company)); ?>" required>
            </div>

            <div class="input-group">
                <label>Type:</label>
                <select name="type" required>
                    <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($type->id); ?>"
                                <?php if(old('type', $subscription->type) == $type->id): ?> selected <?php endif; ?>>
                            <?php echo e($type->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="input-group">
                <label>Start Datum:</label>
                <input type="date" name="start_date" value="<?php echo e(old('start_date', $subscription->start_date)); ?>" required>
            </div>

            <div class="input-group">
                <label>Eind Datum:</label>
                <input type="date" name="end_date" value="<?php echo e(old('end_date', $subscription->end_date)); ?>">
            </div>

            <div class="input-group">
                <label>Prijs:</label>
                <input type="text" name="price" value="<?php echo e(old('price', $subscription->price)); ?>" required>
            </div>

            <div class="input-group">
                <label>Identifier:</label>
                <input type="text" name="identifier" value="<?php echo e(old('identifier', $subscription->identifier)); ?>" required>
            </div>

            <div class="input-group">
                <label>Notes:</label>
                <textarea name="notes"><?php echo e(old('notes', $subscription->notes)); ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Opslaan</button>
        </form>

        <!-- Knop om naar station-beheer te gaan -->
        <div style="margin-top: 20px;">
            <a href="<?php echo e(route('manage-subscriptions.editStations', $subscription->id)); ?>" class="btn btn-info">
                Stations beheren
            </a>
            <a href="<?php echo e(route('manage-subscriptions.index')); ?>" class="btn btn-secondary">Terug</a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\carst\OneDrive\Documenten\GitHub\Project-WAP\resources\views/subscriptionEdit.blade.php ENDPATH**/ ?>