@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Criterium #{{ $criterium->id }}</h1>

        <p><strong>Omschrijving:</strong> {{ $criterium->omschrijving }}</p>
        <p><strong>Aangemaakt op:</strong> {{ $criterium->created_at }}</p>

        <a href="{{ route('contracts.queries.criteria.index', [$contract, $query]) }}" class="btn btn-secondary">
            Terug naar Criteria
        </a>
    </div>
@endsection
