@extends('layouts.app')

@section('content')
    <div class="login-container" style="margin-top: 40px;">
        <div class="login-header">
            <h2 style="color: #007bff;">New Contract</h2>
        </div>

        @if ($errors->any())
            <div class="error-message">
                <ul style="padding-left: 20px; margin: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('contracts.store') }}" method="POST">
            @csrf

            <div class="input-group">
                <label for="identifier">Identifier</label>
                <input type="text" name="identifier" id="identifier" value="{{ old('identifier') }}" required>
            </div>

            <div class="input-group">
                <label for="company_id">Company ID</label>
                <input type="number" name="company_id" id="company_id" value="{{ old('company_id') }}">
            </div>

            <div class="input-group">
                <label for="omschrijving">Description</label>
                <input type="text" name="omschrijving" id="omschrijving" value="{{ old('omschrijving') }}">
            </div>

            <div class="input-group">
                <label for="start_datum">Start Date</label>
                <input type="date" name="start_datum" id="start_datum" value="{{ old('start_datum') }}">
            </div>

            <div class="input-group">
                <label for="eind_datum">End Date</label>
                <input type="date" name="eind_datum" id="eind_datum" value="{{ old('eind_datum') }}">
            </div>

            <div class="input-group">
                <label for="url">URL</label>
                <input type="url" name="url" id="url" value="{{ old('url') }}">
            </div>

            <button type="submit" class="login-button">💾 Save Contract</button>
            <a href="{{ route('contracts.index') }}" class="back-button" style="margin-top: 10px; display: inline-block;">← Cancel</a>
        </form>
    </div>
@endsection
