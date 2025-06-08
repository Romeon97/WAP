@extends('layouts.app')

@section('content')
    <div class="login-wrapper">
        <div class="login-container">
            <div class="login-box">
                <div class="login-header">
                    <img src="{{ asset('images/LogoIWANEW.png') }}" alt="Weer App Logo" class="login-logo">
                    <h2>Login</h2>
                </div>

                @if (session('error'))
                    <p class="error-message">{{ session('error') }}</p>
                @endif

                <form action="{{ route('login.process') }}" method="POST">
                    @csrf
                    <div class="input-group">
                        <label for="username">Employee code</label>
                        <input type="text" name="username" required>
                    </div>

                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" required>
                    </div>

                    <button type="submit" class="login-button">Login</button>

                    <div class="extra-links">
                        <a href="{{ url('/') }}" class="back-button">← Back to home</a>
                        <h5></h5>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
