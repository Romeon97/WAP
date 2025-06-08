@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="page-title">🟢 Active Stations (last 14 days)</h1>
        <form method="GET" action="{{ route('monitoring.active') }}">
            <div class="flex gap-4">
                <div>
                    <label for="filter_type">Filter by:</label>
                    <select name="filter_type" id="filter_type" onchange="this.form.submit()">
                        <option value="region" {{ $filterType == 'region' ? 'selected' : '' }}>Region</option>
                        <option value="country" {{ $filterType == 'country' ? 'selected' : '' }}>Country</option>
                    </select>
                </div>

                <div>
                    <label for="filter_value">
                        {{ $filterType == 'region' ? 'Choose a region:' : 'Choose a country:' }}
                    </label>
                    <select name="filter_value" id="filter_value" onchange="this.form.submit()">
                        <option value="">-- All --</option>
                        @if ($filterType == 'region')
                            @foreach ($regions as $region)
                                <option value="{{ $region }}" {{ $filterValue == $region ? 'selected' : '' }}>{{ $region }}</option>
                            @endforeach
                        @elseif ($filterType == 'country')
                            @foreach ($countries as $country)
                                <option value="{{ $country }}" {{ $filterValue == $country ? 'selected' : '' }}>{{ $country }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div>
                <label for="search">Search station:</label>
                <input type="text" name="search" id="search" placeholder="e.g. 703600" value="{{ $search }}" class="px-2 py-1 border rounded" />
                <button type="submit" class="ml-2 px-3 py-1 bg-blue-500 text-white rounded">Search</button>
            </div>
        </form>

        @if (!$stations->isEmpty())
            <div id="station-map" style="height: 500px; width: 100%;" class="mb-6 rounded-lg shadow"></div>

            <div id="station-list" class="station-grid">
                @include('partials.station-cards', ['stations' => $stations])
            </div>
        @else
            <div class="w-full flex justify-center items-center mt-12">
                <div class="text-center bg-yellow-100 text-yellow-900 border border-yellow-300 p-6 rounded-lg shadow-md max-w-xl w-full">
                    <h2 class="text-2xl font-bold mb-2">No stations found</h2>

                    @if ($search)
                        <p>No stations match your search<br>for: <strong>{{ $search }}</strong></p>
                    @elseif ($filterType === 'country' && $filterValue)
                        <p>No stations found for country: <strong>{{ $filterValue }}</strong></p>
                    @elseif ($filterType === 'region' && $filterValue)
                        <p>No stations found for region: <strong>{{ $filterValue }}</strong></p>
                    @else
                        <p>We couldn’t find any stations with the current filters.</p>
                    @endif

                    <a href="{{ route('monitoring.active') }}" class="inline-block mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded shadow">
                        Reset filters
                    </a>
                </div>
            </div>
        @endif
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />

    <script>
        const map = L.map('station-map').setView([20, 0], 2);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const markers = L.markerClusterGroup({ showCoverageOnHover: false });

        const stations = @json($stations);
        const bounds = L.latLngBounds([]);

        stations.forEach(station => {
            if (station.latitude && station.longitude) {
                const marker = L.marker([station.latitude, station.longitude]);
                marker.bindPopup(`
                <strong><a href="/station/${station.station_name}" >${station.station_name}</a></strong><br>
                Country: ${station.country_code ?? '??'}
            `);
                markers.addLayer(marker);
                bounds.extend([station.latitude, station.longitude]);
            }
        });

        map.addLayer(markers);
        if (bounds.isValid()) {
            map.fitBounds(bounds);
        }
    </script>

@endsection
