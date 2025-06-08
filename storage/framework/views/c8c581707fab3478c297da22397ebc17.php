<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1 class="page-title">Abonnementen Beheer</h1>

        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <!-- Knop voor nieuw abonnement -->
        <button class="register-button" onclick="document.getElementById('addSubscriptionForm').style.display='block'">
            Nieuw abonnement toevoegen
        </button>

        <!-- Form voor nieuw abonnement -->
        <div id="addSubscriptionForm" class="bedrijf-form hidden">
            <h2>Nieuw Abonnement</h2>
            <form method="POST" action="<?php echo e(route('manage-subscriptions.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="input-group">
                    <label>Company:</label>
                    <select name="company" required>
                        <option value="">-- Kies een bedrijf --</option>
                        <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($company->id); ?>">
                                <?php echo e($company->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="input-group">
                    <label>Type:</label>
                    <select name="type" required>
                        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="input-group">
                    <label>Start Datum:</label>
                    <input type="date" name="start_date" required>
                </div>
                <div class="input-group">
                    <label>Eind Datum:</label>
                    <input type="date" name="end_date">
                </div>
                <div class="input-group">
                    <label>Prijs:</label>
                    <input type="text" name="price" required>
                </div>
                <div class="input-group">
                    <label>Identifier:</label>
                    <input type="text" name="identifier" required>
                </div>
                <div class="input-group">
                    <label>Notes:</label>
                    <textarea name="notes"></textarea>
                </div>
                <div class="button-group">
                    <button class="register-button" type="submit">Opslaan</button>
                    <button class="back-button" type="button" onclick="document.getElementById('addSubscriptionForm').style.display='none'">Annuleren</button>
                </div>
            </form>
        </div>

        <!-- Filter -->
        <form method="GET" action="<?php echo e(route('manage-subscriptions.index')); ?>" style="margin-top: 30px;">
            <label for="type">Filter op type:</label>
            <select name="type" id="type">
                <option value="">Alle types</option>
                <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($type->id); ?>" <?php echo e((isset($selectedType) && $selectedType == $type->id) ? 'selected' : ''); ?>>
                        <?php echo e($type->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button class="map-btn" type="submit">Filter</button>
        </form>

        <!-- Tabel met abonnementen -->
        <div class="table-striped">
            <table class="user-table">
                <thead>
                <tr>
                    <th>Company</th>
                    <th>Type</th>
                    <th>Start Datum</th>
                    <th>Eind Datum</th>
                    <th>Prijs</th>
                    <th>Identifier</th>
                    <th>Token</th>
                    <th>Notes</th>
                    <th>Acties</th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <!-- Klikbaar via de bedrijfsnaam: -->
                        <td>
                            <a href="<?php echo e(route('manage-subscriptions.edit', $subscription->id)); ?>">
                                <?php echo e($subscription->companyRelation->name ?? 'Onbekend'); ?>

                            </a>
                        </td>
                        <td><?php echo e($subscription->subscriptionType->name ?? 'Onbekend'); ?></td>
                        <td><?php echo e($subscription->start_date); ?></td>
                        <td><?php echo e($subscription->end_date); ?></td>
                        <td><?php echo e($subscription->price); ?></td>
                        <td><?php echo e($subscription->identifier); ?></td>
                        <td><?php echo e($subscription->token); ?></td>
                        <td><?php echo e($subscription->notes); ?></td>
                        <td class="action-buttons">
                            <!-- Verwijderen -->
                            <form action="<?php echo e(route('manage-subscriptions.destroy', $subscription->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button class="delete-btn" type="submit" onclick="return confirm('Weet je het zeker?')">
                                    Verwijderen
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function toggleEditForm(id) {
            var formRow = document.getElementById('edit-form-' + id);
            if (formRow) {
                formRow.classList.toggle('hidden');
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\carst\OneDrive\Documenten\GitHub\Project-WAP\resources\views/subscriptionManagerView.blade.php ENDPATH**/ ?>