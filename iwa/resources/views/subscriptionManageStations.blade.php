@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="page-title">Stations for Subscription: {{ $subscription->identifier }}</h1>

        <div class="subscription-details" style="margin-bottom: 2rem; text-align: left; max-width: 700px; margin-inline: auto;">
            <p><strong>Company:</strong> {{ $subscription->companyRelation->name ?? 'Unknown' }}</p>
            <p><strong>Type:</strong> {{ $subscription->subscriptionType->name ?? 'Unknown' }}</p>
            <p><strong>Start date:</strong> {{ $subscription->start_date }}</p>
            <p><strong>End date:</strong> {{ $subscription->end_date ?? 'N/A' }}</p>
            <p><strong>Price:</strong> €{{ number_format($subscription->price, 2) }}</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="max-width: 700px; margin: 0 auto 2rem auto;">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('manage-subscriptions.updateStations', $subscription->id) }}" style="max-width: 800px; margin: 0 auto;">
            @csrf

            <div class="form-group" style="text-align: left; margin-bottom: 2rem;">
                <label for="stationSearch"><strong>Search stations:</strong></label>
                <input type="text" id="stationSearch" placeholder="Type to search..." style="width: 100%; padding: 10px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ccc;">

                <div class="station-grid" id="stationGrid">
                    @foreach($allStations as $station)
                        <label class="station-card" style="cursor: pointer;">
                            <input type="checkbox" name="stations[]" value="{{ $station->name }}"
                                {{ in_array($station->name, $linkedStations) ? 'checked' : '' }}>
                            <h3>{{ $station->name }}</h3>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="button-group" style="margin-top: 2rem;">
                <button type="submit" class="register-button">💾 Save Stations</button>
                <a href="{{ route('manage-subscriptions.index') }}" class="back-button">← Back to Subscriptions</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('stationSearch').addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();
            const cards = document.querySelectorAll('#stationGrid .station-card');

            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    card.style.display = 'inline-block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
@endsection
