<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'International Weather Agency'); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('css/styles.css')); ?>">
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/LogoIWANEW.png')); ?>">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
</head>
<body>
<header>
    <div class="logo-container">
        <img src="<?php echo e(asset('images/LogoIWANEW.png')); ?>" alt="Weer App Logo" class="logo">
        <h2>INTERNATIONAL WEATHER AGENCY</h2>
    </div>
    <nav>
        <ul>
            <li><a href="/" class="home-button">Home</a></li>

            <!-- 🔹 Admin dropdown-menu alleen voor gebruikers met role_id 6 -->
            <?php if(auth()->check() && auth()->user()->user_role == 6): ?>
                <li class="admin-menu">
                    <a href="#" class="admin-icon">⚙️ Menu ▾</a>
                    <ul class="admin-dropdown">
                        <li><a href="<?php echo e(route('admin.manageUsers')); ?>">Gebruikers</a></li>
                        <li><a href="<?php echo e(route('bedrijven.index')); ?>">Bedrijven</a></li>
                        <li><a href="<?php echo e(route('manage-subscriptions.index')); ?>">Abonnementen</a></li>
                        <li><a href="<?php echo e(route('logs.index')); ?>">Logs</a></li>
                        <li><a href="<?php echo e(route('monitoring.index')); ?>">Monitoring Stations</a></li>
                    </ul>
                </li>
            <?php endif; ?>
            <?php if(auth()->check() && auth()->user()->user_role == 4): ?>
                <li class="admin-menu">
                    <a href="#" class="admin-icon">⚙️ Menu ▾</a>
                    <ul class="admin-dropdown">
                        <li><a href="<?php echo e(route('admin.manageUsers')); ?>">Gebruikers</a></li>
                    </ul>
                </li>
            <?php endif; ?>
            <?php if(auth()->check() && auth()->user()->user_role == 3): ?>
                <li class="admin-menu">
                    <a href="#" class="admin-icon">⚙️ Menu ▾</a>
                    <ul class="admin-dropdown">
                        <li><a href="<?php echo e(route('bedrijven.index')); ?>">Bedrijven</a></li>
                        <li><a href="<?php echo e(route('manage-subscriptions.index')); ?>">Abonnementen</a></li>
                    </ul>
                </li>
            <?php endif; ?>
            <?php if(auth()->check() && auth()->user()->user_role == 5): ?>
                <li class="admin-menu">
                    <a href="#" class="admin-icon">⚙️ Menu ▾</a>
                    <ul class="admin-dropdown">
                        <li><a href="<?php echo e(route('logs.index')); ?>">Logs</a></li>
                    </ul>
                </li>
            <?php endif; ?>
            <?php if(auth()->check() && auth()->user()->user_role == 1): ?>
                <li class="admin-menu">
                    <a href="#" class="admin-icon">⚙️ Menu ▾</a>
                    <ul class="admin-dropdown">
                        <li><a href="<?php echo e(route('monitoring.index')); ?>">Monitoring</a></li>
                    </ul>
                </li>
                <?php if(auth()->check() && auth()->user()->user_role == 2): ?>
                    <li class="admin-menu">
                        <a href="#" class="admin-icon">⚙️ Menu ▾</a>
                        <ul class="admin-dropdown">
                            <li><a href="<?php echo e(route('monitoring.index')); ?>">Monitoring</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            <?php endif; ?>



        </ul>

        <div class="user-status">
            <span>
                <?php echo e(session('user', 'Niet ingelogd')); ?>

                <?php if(session()->has('user_role')): ?>
                    (<?php echo e(session('user_role')); ?>)
                <?php endif; ?>
            </span>

            <?php if(session()->has('user')): ?>
                <form action="<?php echo e(route('logout')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="logout-button">Log uit</button>
                </form>
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="login-button">Login</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<main>
    <?php echo $__env->yieldContent('content'); ?>
</main>

<footer class="footer-right">
    <p>&copy; <?php echo e(date('Y')); ?> International Weather Agency</p>
</footer>
</body>
</html>
<?php /**PATH C:\Users\carst\OneDrive\Documenten\GitHub\Project-WAP\resources\views/layouts/app.blade.php ENDPATH**/ ?>