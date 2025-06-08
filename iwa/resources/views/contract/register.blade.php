@extends('layouts.contracts')

@if (!session('contract_user_identifier') || session('contract_user_is_admin') !== 1)
    <script>window.location.href = '/contract/login';</script>
@endif

@section('title', 'Add New User')

@section('content')
    <h1>➕ Add New User</h1>

    {{-- Back to user list --}}
    <a href="/contract/users" style="display: inline-block; margin-bottom: 1rem; color: #007bff;">← Back to user list</a>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if ($errors->any())
        <ul style="color: red;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('contractusers.store') }}" method="POST" style="max-width: 500px;">
        @csrf
        <div style="margin-bottom: 1rem;">
            <label for="useridentifier">User ID:</label>
            <input type="text" name="useridentifier" id="useridentifier" class="form-control" required>
        </div>

        <div style="margin-bottom: 1rem;">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div style="margin-bottom: 1rem;">
            <label for="email">Email address:</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div style="margin-bottom: 1rem;">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <div style="margin-bottom: 1rem;">
            <label for="role">Role:</label>
            <select name="role" id="role" class="form-control" required>
                <option value="klant">Client</option>
                <option value="beheerder">Administrator</option>
            </select>
        </div>

        <div style="margin-bottom: 1rem;">
            <label for="is_admin">Admin?</label>
            <select name="is_admin" id="is_admin" class="form-control" required>
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </div>

        <button type="submit" style="background-color: #007bff; color: white; padding: 0.5rem 1rem; border: none; border-radius: 5px;">Add User</button>
    </form>
@endsection
