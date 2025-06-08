@extends('layouts.app')

@section('content')
    <div class="login-container" style="margin-top: 40px;">
        <div class="login-header">
            <h2 style="color: #007bff;">Contract: {{ $contract->identifier }}</h2>
        </div>

        <div style="text-align: left; margin-top: 20px;">
            <p><strong>ID:</strong> {{ $contract->id }}</p>
            <p><strong>Description:</strong> {{ $contract->omschrijving }}</p>
            <p><strong>Start Date:</strong> {{ $contract->start_datum }}</p>
            <p><strong>End Date:</strong> {{ $contract->eind_datum }}</p>
            <p><strong>URL:</strong> <a href="{{ $contract->url }}" target="_blank">{{ $contract->url }}</a></p>
        </div>

        <a href="{{ route('contracts.index') }}" class="back-button" style="margin-top: 20px; display: inline-block;">
            ← Back to list
        </a>
    </div>
@endsection
