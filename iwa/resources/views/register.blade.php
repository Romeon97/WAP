@extends('layouts.app')

@section('content')

    @if ($errors->any())
        <div class="error-message">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-wrapper">
        <div class="login-container">
            <div class="login-header">
                <img src="/images/LogoIWANEW.png" alt="Logo" class="login-logo">
                <h2>Gebruiker aanmaken</h2>
            </div>

            @if(session('error'))
                <div class="error-message">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('register.process') }}">
                @csrf

                <div class="input-group">
                    <label for="name">Achternaam</label>
                    <input id="name" type="text" name="name" required>
                </div>

                <div class="input-group">
                    <label for="first_name">Voornaam</label>
                    <input id="first_name" type="text" name="first_name" required>
                </div>

                <div class="input-group">
                    <label for="initials">Initialen</label>
                    <input id="initials" type="text" name="initials">
                </div>

                <div class="input-group">
                    <label for="prefix">Tussenvoegsel</label>
                    <input id="prefix" type="text" name="prefix">
                </div>

                <div class="input-group">
                    <label for="email">E-mailadres</label>
                    <input id="email" type="email" name="email" required>
                </div>

                <div class="input-group">
                    <label for="employee_code">Medewerker ID</label>
                    <input id="employee_code" type="text" name="employee_code" required>
                </div>

                <div class="input-group">
                    <label for="user_role">Rol</label>
                    <select name="user_role" id="user_role" required>
                        <option value="" disabled selected>Selecteer een rol</option>
                        @foreach($roles as $role)
                            @if(auth()->user()->user_role == 6 || !in_array($role->id, [4, 6]))
                                <option value="{{ $role->id }}">{{ $role->role }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="input-group">
                    <label for="password">Wachtwoord</label>
                    <input id="password" type="password" name="password" required>
                </div>

                <div class="input-group">
                    <label for="password_confirmation">Bevestig wachtwoord</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                </div>

                <button type="submit" class="login-button">Gebruiker aanmaken</button>
            </form>

            <div class="extra-links">
                <a href="{{ route('admin.manageUsers') }}">Terug naar overzicht</a>
            </div>
        </div>
    </div>
@endsection
