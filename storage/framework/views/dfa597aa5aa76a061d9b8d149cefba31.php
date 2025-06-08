<?php $__env->startSection('content'); ?>
    <div class="login-wrapper">
        <div class="login-container">
            <div class="login-box">
                <div class="login-header">
                    <img src="<?php echo e(asset('images/LogoIWANEW.png')); ?>" alt="Weer App Logo" class="login-logo">
                    <h2>Inloggen</h2>
                </div>

                <?php if(session('error')): ?>
                    <p class="error-message"><?php echo e(session('error')); ?></p>
                <?php endif; ?>

                <form action="<?php echo e(route('login.process')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="input-group">
                        <label for="username">👤 Gebruikersnaam</label>
                        <input type="text" name="username" required>
                    </div>

                    <div class="input-group">
                        <label for="password">🔑 Wachtwoord</label>
                        <input type="password" name="password" required>
                    </div>

                    <button type="submit" class="login-button">Log in</button>

                    <div class="extra-links">
                        <a href="<?php echo e(url('/')); ?>" class="back-button">← Terug naar home</a>
                        <h5></h5>
                    </div>

                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\carst\OneDrive\Documenten\GitHub\Project-WAP\resources\views/login.blade.php ENDPATH**/ ?>