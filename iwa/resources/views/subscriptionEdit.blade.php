@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit subscription</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('manage-subscriptions.update', $subscription->id) }}">
            @csrf
            @method('PUT')

            <div class="input-group">
                <label>Company:</label>
                <input type="number" name="company" value="{{ old('company', $subscription->company) }}" required>
            </div>

            <div class="input-group">
                <label>Type:</label>
                <select name="type" required>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}"
                                @if(old('type', $subscription->type) == $type->id) selected @endif>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="input-group">
                <label>Start date:</label>
                <input type="date" name="start_date" value="{{ old('start_date', $subscription->start_date) }}" required>
            </div>

            <div class="input-group">
                <label>End date:</label>
                <input type="date" name="end_date" value="{{ old('end_date', $subscription->end_date) }}">
            </div>

            <div class="input-group">
                <label>Price:</label>
                <input type="text" name="price" value="{{ old('price', $subscription->price) }}" required>
            </div>

            <div class="input-group">
                <label>Identifier:</label>
                <input type="text" name="identifier" value="{{ old('identifier', $subscription->identifier) }}" required>
            </div>

            <div class="input-group">
                <label>Notes:</label>
                <textarea name="notes">{{ old('notes', $subscription->notes) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>

        <!-- Knop om naar station-beheer te gaan -->
        <div style="margin-top: 20px;">
            <a href="{{ route('manage-subscriptions.editStations', $subscription->id) }}" class="btn btn-info">
                Manage stations
            </a>
            <a href="{{ route('manage-subscriptions.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
@endsection
