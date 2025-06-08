<?php $__env->startSection('content'); ?>
    <div class="register-wrapper">
        <div class="register-container">
            <div class="register-box">
                <h2>Registreren</h2>

                <?php if($errors->any()): ?>
                    <p class="error-message"><?php echo e($errors->first()); ?></p>
                <?php endif; ?>

                <form action="<?php echo e(route('register.process')); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <!-- 🔹 Voornaam -->
                    <div class="input-group">
                        <label for="first_name">👤 Voornaam</label>
                        <input type="text" name="first_name" required>
                    </div>

                    <!-- 🔹 Tussenvoegsel (Optioneel) -->
                    <div class="input-group">
                        <label for="prefix">🆔 Tussenvoegsel</label>
                        <input type="text" name="prefix">
                    </div>

                    <!-- 🔹 Achternaam -->
                    <div class="input-group">
                        <label for="name">🆔 Achternaam</label>
                        <input type="text" name="name" required>
                    </div>

                    <!-- 🔹 Initialen (Optioneel) -->
                    <div class="input-group">
                        <label for="initials">🔤 Initialen</label>
                        <input type="text" name="initials">
                    </div>

                    <!-- 🔹 E-mail -->
                    <div class="input-group">
                        <label for="email">📧 E-mail</label>
                        <input type="email" name="email" required>
                    </div>

                    <!-- 🔹 Gebruikersnaam -->
                    <div class="input-group">
                        <label for="employee_code">🆔 Gebruikersnaam</label>
                        <input type="text" name="employee_code" required>
                    </div>

                    <!-- 🔹 Wachtwoord -->
                    <div class="input-group">
                        <label for="password">🔑 Wachtwoord</label>
                        <input type="password" name="password" required>
                    </div>

                    <!-- 🔹 Bevestig Wachtwoord -->
                    <div class="input-group">
                        <label for="password_confirmation">🔑 Bevestig Wachtwoord</label>
                        <input type="password" name="password_confirmation" required>
                    </div>

                    <!-- 🔹 Dropdown voor User Role -->
                    <div class="input-group">
                        <label for="user_role">👔 Kies een rol</label>
                        <select name="user_role" required>
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($role->id); ?>"><?php echo e($role->role); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="button-group">
                        <button type="submit" class="register-button">Registreren</button>
                        <a href="<?php echo e(url('/')); ?>" class="back-button">← Terug naar home</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\carst\OneDrive\Documenten\GitHub\Project-WAP\resources\views/register.blade.php ENDPATH**/ ?>