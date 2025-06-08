@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="page-title">Query: {{ $query->omschrijving }} (Contract: {{ $contract->identifier }})</h1>

        <div class="table-wrapper shadow bg-white p-4 rounded" style="max-width: 600px; margin: 0 auto;">
            <p><strong>ID:</strong> {{ $query->id }}</p>
            <p><strong>Description:</strong> {{ $query->omschrijving }}</p>
            <p><strong>Created at:</strong> {{ $query->created_at }}</p>
        </div>

        <div class="button-group" style="margin-top: 2rem;">
            <a href="{{ route('contracts.queries.index', $contract) }}" class="back-button">← Back to Queries</a>
        </div>
    </div>
@endsection
