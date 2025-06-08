@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Nieuw Criterium toevoegen aan Query: {{ $query->omschrijving }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('contracts.queries.criteria.store', [$contract, $query]) }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Omschrijving</label>
                <input type="text" name="omschrijving" class="form-control" value="{{ old('omschrijving') }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Opslaan</button>
            <a href="{{ route('contracts.queries.criteria.index', [$contract, $query]) }}" class="btn btn-secondary">
                Annuleren
            </a>
        </form>
    </div>
@endsection
