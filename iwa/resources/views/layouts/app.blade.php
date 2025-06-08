<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'International Weather Agency')</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/LogoIWANEW.png') }}">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
</head>
<body>
<header>
    <div class="logo-container">
        <img src="{{ asset('images/LogoIWANEW.png') }}" alt="Weer App Logo" class="logo">
        <h2>INTERNATIONAL WEATHER AGENCY</h2>
    </div>
    <nav>
        <ul>
            <li><a href="/" class="home-button">Home</a></li>

            <!-- 🔹 Admin dropdown-menu alleen voor gebruikers met role_id 6 -->
            @if(auth()->check() && auth()->user()->user_role == 6)
                <li class="admin-menu">
                    <a href="#" class="admin-icon">Menu ▾</a>
                    <ul class="admin-dropdown">
                        <li><a href="{{ route('admin.manageUsers') }}">Users</a></li>
                        <li><a href="{{ route('bedrijven.index') }}">Companies</a></li>
                        <li><a href="{{ route('manage-subscriptions.index') }}">Subscriptions</a></li>
                        <li><a href="{{ route('logs.index') }}">Logs</a></li>
                        <li><a href="{{ route('monitoring.index') }}">Station Monitoring</a></li>
                        <li><a href="{{ route('contracts.index') }}">Contracts</a></li>
                    </ul>
                </li>
            @endif
            @if(auth()->check() && auth()->user()->user_role == 4)
                <li class="admin-menu">
                    <a href="#" class="admin-icon">Menu ▾</a>
                    <ul class="admin-dropdown">
                        <li><a href="{{ route('admin.manageUsers') }}">Users</a></li>
                    </ul>
                </li>
            @endif
            @if(auth()->check() && auth()->user()->user_role == 3)
                <li class="admin-menu">
                    <a href="#" class="admin-icon">Menu ▾</a>
                    <ul class="admin-dropdown">
                        <li><a href="{{ route('bedrijven.index') }}">Companies</a></li>
                        <li><a href="{{ route('manage-subscriptions.index') }}">Subscriptions</a></li>
                        <li><a href="{{ route('contracts.index') }}">Contracts</a></li>
                    </ul>
                </li>
            @endif
            @if(auth()->check() && auth()->user()->user_role == 5)
                <li class="admin-menu">
                    <a href="#" class="admin-icon">Menu ▾</a>
                    <ul class="admin-dropdown">
                        <li><a href="{{ route('logs.index') }}">Logs</a></li>
                    </ul>
                </li>
            @endif
            @if(auth()->check() && auth()->user()->user_role == 1)
                <li class="admin-menu">
                    <a href="#" class="admin-icon">Menu ▾</a>
                    <ul class="admin-dropdown">
                        <li><a href="{{ route('monitoring.index') }}">Station Monitoring</a></li>
                    </ul>
                </li>
                @if(auth()->check() && auth()->user()->user_role == 2)
                    <li class="admin-menu">
                        <a href="#" class="admin-icon">Menu ▾</a>
                        <ul class="admin-dropdown">
                            <li><a href="{{ route('monitoring.index') }}">Station Monitoring</a></li>
                        </ul>
                    </li>
                @endif
            @endif



        </ul>

        <div class="user-status">
            <span>
                {{ session('user', 'Not signed in') }}
                @if(session()->has('user_role'))
                    ({{ session('user_role') }})
                @endif
            </span>

            @if (session()->has('user'))
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-button">Log out</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="login-button">Login</a>
            @endif
        </div>
    </nav>
</header>

<main>
    @yield('content')
</main>

<footer class="footer-right">
    <p>&copy; {{ date('Y') }} International Weather Agency</p>
</footer>
</body>
</html>
