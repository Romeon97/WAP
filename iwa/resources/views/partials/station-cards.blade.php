@foreach ($stations as $station)
    @php
        $link = is_callable($stationLink ?? null)
            ? $stationLink($station)
            : ($stationLink ?? route('station.measurements', $station->station_name));
    @endphp

    <a href="{{ $link }}" class="station-card">
        <h3>{{ $station->station_name }}</h3>
        <p>Country: {{ $station->country_name ?? 'Unknown' }}</p>
        @if (isset($station->error_count))
            <p class="text-sm text-red-500 mt-1">Error count: {{ $station->error_count }}</p>
        @endif
    </a>
@endforeach

