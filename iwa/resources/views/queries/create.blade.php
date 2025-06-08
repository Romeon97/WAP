@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="page-title">Add New Query to Contract: {{ $contract->identifier }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('contracts.queries.store', $contract) }}" method="POST"
              style="max-width: 600px; margin: 0 auto;">
            @csrf

            <div class="form-group">
                <label>Description</label>
                <input type="text" name="omschrijving" class="form-control"
                       value="{{ old('omschrijving') }}" required>
            </div>

            <div class="button-group" style="margin-top: 2rem;">
                <button type="submit" class="register-button">➕ Create Query</button>
                <a href="{{ route('contracts.queries.index', $contract) }}" class="back-button">← Cancel</a>
            </div>
        </form>
    </div>
@endsection
