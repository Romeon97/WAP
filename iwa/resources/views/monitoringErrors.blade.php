@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="page-title">🚨 Active errors</h1>

        @if ($stations->isEmpty())
            <p>There are no active errors at the moment</p>
        @else
            <div id="station-list" class="station-grid">
                @include('partials.station-cards', [
    'stations' => $stations,
    'stationLink' => function($station) {
        return route('monitoring.errors.detail', $station->station_name);
    }
])
            </div>
        @endif
    </div>
@endsection


