@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Nieuwe CriteriumGroup aanmaken voor query: {{ $query->omschrijving }}</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('contracts.queries.groups.store', [$contract, $query]) }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Type</label>
                <input type="number" name="type" class="form-control" value="{{ old('type') }}" required>
            </div>
            <div class="form-group">
                <label>Group Level</label>
                <input type="number" name="group_level" class="form-control" value="{{ old('group_level') }}" required>
            </div>
            <div class="form-group">
                <label>Operator</label>
                <input type="number" name="operator" class="form-control" value="{{ old('operator') }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Opslaan</button>
            <a href="{{ route('contracts.queries.groups.index', [$contract, $query]) }}"
               class="btn btn-secondary">
                Annuleren
            </a>
        </form>
    </div>
@endsection
