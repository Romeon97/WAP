<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inloggen - Contractomgeving</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f4f8;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .login-container {
            background: white;
            padding: 2rem 2.5rem;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1.25rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 0.75rem;
            background-color: #007bff;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        #errorMessage {
            color: red;
            text-align: center;
            margin-bottom: 1rem;
        }

        footer {
            margin-top: 2rem;
            text-align: center;
            color: #888;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h1>Login</h1>

    <p id="errorMessage"></p>

    <form id="loginForm">
        <label for="useridentifier">User-ID</label>
        <input type="text" name="useridentifier" id="useridentifier" placeholder="User-ID" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Password" required>

        <button type="submit">Login</button>
    </form>

    <footer>&copy; {{ date('Y') }} International Weather Agency</footer>
</div>

<script>
    const form = document.getElementById('loginForm');
    const errorMessage = document.getElementById('errorMessage');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const data = {
            useridentifier: form.useridentifier.value,
            password: form.password.value
        };

        const response = await fetch('/contract/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });

        const json = await response.json();

        if (!response.ok) {
            errorMessage.textContent = json.message || 'Inloggen mislukt';
        } else {
            sessionStorage.setItem('user', JSON.stringify({
                useridentifier: json.user.useridentifier,
                name: json.user.name,
                email: json.user.email,
                role: json.user.role,
                is_admin: json.user.is_admin,
                contractID: json.user.contractID,
                contractIdentifier: json.contract_identifier
            }));
            sessionStorage.setItem('token', json.token);
            window.location.href = '/contract/dashboard';
        }
    });
</script>

</body>
</html>
