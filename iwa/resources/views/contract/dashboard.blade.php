@extends('layouts.contracts')

@section('title', 'Contract Dashboard')

@section('content')
    <h2>📊 Welcome to the Contract Dashboard</h2>

    <p>Manage your users and contract details as an organization administrator.</p>

    <p>User email address: <strong>{{ $email }}</strong></p>
    <p>Token for this contract: <strong>{{ $subscriptionToken }}</strong></p>

    <p>Total users in this contract: <strong>{{ $userCount }}</strong></p>
    <p>Number of administrators: <strong>{{ $adminCount }}</strong></p>

    @if(session('contract_user_is_admin') === 1)
        <div style="margin-top: 2rem;">
            <a href="/contract/users" class="dashboard-link">👥 Manage users</a><br><br>
            <a href="/contract/register" class="dashboard-link">➕ Register new user</a>
        </div>
    @endif

    <style>
        .dashboard-link {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 0.75rem 1.25rem;
            text-decoration: none;
            border-radius: 5px;
            margin: 0.5rem 0;
            transition: background-color 0.2s ease;
            font-weight: bold;
        }

        .dashboard-link:hover {
            background-color: #0056b3;
        }
    </style>
@endsection
