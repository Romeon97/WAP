<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Contractomgeving')</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/LogoIWANEW.png') }}">
    <style>
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background-color: #f8f8f8;
            border-bottom: 1px solid #ddd;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-container img {
            height: 50px;
        }

        .dashboard-button a {
            background-color: #007bff;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .dashboard-button a:hover {
            background-color: #0056b3;
        }

        main {
            padding: 2rem;
        }

        footer {
            text-align: right;
            padding: 1rem 2rem;
            border-top: 1px solid #ddd;
            font-size: 0.9rem;
            color: #555;
        }
    </style>
</head>
<body>

<header>
    <div class="logo-container">
        <img src="{{ asset('images/LogoIWANEW.png') }}" alt="IWA Logo">
        <h2 style="margin: 0;">INTERNATIONAL WEATHER AGENCY</h2>
    </div>

    <div class="dashboard-button">
        <a href="/contract/dashboard">🏠 Dashboard</a>

        @if(session('contract_user_identifier'))
            <button onclick="logout()" style="margin-left: 1rem; background-color: #dc3545; color: white; padding: 0.5rem 1rem; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;">
                🔒 Logout
            </button>
        @endif
    </div>


</header>

<main>
    @yield('content')
</main>

<footer>
    &copy; {{ date('Y') }} International Weather Agency
</footer>
<script>
    function logout() {
        fetch('/contract/logout', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(() => {
            sessionStorage.clear();
            window.location.href = '/contract/login';
        });
    }
</script>

</body>
</html>
