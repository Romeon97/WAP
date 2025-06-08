@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="page-title">Queries for Contract: {{ $contract->identifier }}</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('contracts.queries.create', $contract) }}" class="register-button mb-3">
            ➕ Add New Query
        </a>

        @if($queries->isEmpty())
            <p>No queries found for this contract.</p>
        @else
            <div class="table-wrapper shadow bg-white p-4 rounded">
                <table class="user-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Description</th>
                        <th>Created at</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($queries as $query)
                        <tr>
                            <td>{{ $query->id }}</td>
                            <td>{{ $query->omschrijving }}</td>
                            <td>{{ $query->created_at }}</td>
                            <td class="action-buttons">
                                <a href="{{ route('contracts.queries.groups.index', [$contract, $query]) }}" class="save-btn">
                                    Groups
                                </a>
                                <a href="{{ route('contracts.queries.show', [$contract, $query]) }}" class="save-btn">
                                    Details
                                </a>
                                <a href="{{ route('contracts.queries.edit', [$contract, $query]) }}" class="save-btn" style="padding: 6px 10px;">
                                    Edit
                                </a>
                                <form method="POST"
                                      action="{{ route('contracts.queries.destroy', [$contract, $query]) }}"
                                      style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="delete-btn" onclick="return confirm('Are you sure?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
