@extends('layouts.app')

@section('title', 'Contracts')

@section('content')
    <div class="container">
        <h1 class="page-title">Contracts</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Knop: nieuw contract -->
        <a href="{{ route('contracts.create') }}" class="register-button" style="margin-bottom: 20px;">
            ➕ Create new contract
        </a>

        <table class="user-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Identifier</th>
                <th>Description</th>
                <th style="text-align: center;">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($contracts as $contract)
                <tr>
                    <td>{{ $contract->id }}</td>
                    <td>{{ $contract->identifier }}</td>
                    <td>{{ $contract->omschrijving }}</td>
                    <td style="text-align: center;">
                        <div class="action-buttons">
                            <a href="{{ route('contracts.queries.index', $contract) }}" class="save-btn">Queries</a>
                            <a href="{{ route('contracts.show', $contract) }}" class="save-btn">Details</a>
                            <a href="{{ route('contracts.edit', $contract) }}" class="save-btn">Edit</a>
                            <form method="POST" action="{{ route('contracts.destroy', $contract) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="delete-btn" onclick="return confirm('Are you sure you want to delete this contract?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
