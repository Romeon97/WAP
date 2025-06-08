@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="page-title">🌍 Weather Stations</h1>
        <form method="GET" action="{{ route('nearestlocation.index') }}" class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
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

                    <a href="{{ route('nearestlocation.index') }}" class="inline-block mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded shadow">
                        Reset filters
                    </a>
                </div>
            </div>
        @endif


    @if (request()->ajax() === false)
            <script>
                const filteredStations = @json($stations);
            </script>
        @endif

        @if ($stations instanceof \Illuminate\Pagination\LengthAwarePaginator && $stations->hasMorePages())
            <div class="flex justify-center mt-6">
                <button id="load-more"
                        data-next-page="{{ $stations->currentPage() + 1 }}"
                        class="px-6 py-3 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Show More
                </button>
            </div>
        @endif
    </div>

    <script>
        const map = L.map('station-map').setView([20, 0], 1);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const markers = L.markerClusterGroup({ showCoverageOnHover: false });

        let filteredStationNames = [];
        try {
            filteredStationNames = @json($stations->pluck('station_name'));
        } catch (e) {
            filteredStationNames = [];
        }

        const urlParams = new URLSearchParams(window.location.search);
        const filterType = urlParams.get('filter_type');
        const filterValue = urlParams.get('filter_value');
        const isAll = !filterValue && !urlParams.get('search');

        const query = filterType && filterValue ? `?filter_type=${filterType}&filter_value=${filterValue}` : '';

        fetch(`/stations/map-data${query}`)
            .then(response => response.json())
            .then(stations => {
                const bounds = L.latLngBounds([]);
                let matchCount = 0;

                stations.forEach(station => {
                    if (station.latitude && station.longitude) {
                        // Toon ALLE markers als er geen filter is, anders alleen gefilterde
                        const shouldShow = isAll || filteredStationNames.length === 0 || filteredStationNames.includes(station.station_name);

                        if (shouldShow) {
                            const marker = L.marker([station.latitude, station.longitude]);
                            marker.bindPopup(`
                        <strong><a href="/station/${station.station_name}">${station.station_name}</a></strong><br>
                        Country: ${station.country_code ?? '??'}
                    `);
                            markers.addLayer(marker);

                            // Zoom alleen op gefilterde (of alles als 'All')
                            if (shouldShow) {
                                bounds.extend([station.latitude, station.longitude]);
                                matchCount++;
                            }
                        }
                    }
                });

                map.addLayer(markers);

                if (matchCount > 0 && bounds.isValid()) {
                    map.fitBounds(bounds);
                } else {
                    map.setView([20, 0], 2);
                }
            });



    </script>



    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loadMoreBtn = document.getElementById('load-more');
            if (!loadMoreBtn) return;

            loadMoreBtn.addEventListener('click', function () {
                const nextPage = this.getAttribute('data-next-page');

                fetch(`/stations/load-more?page=${nextPage}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.text())
                    .then(html => {
                        const stationList = document.getElementById('station-list');
                        stationList.insertAdjacentHTML('beforeend', html);

                        const newPage = parseInt(nextPage) + 1;
                        this.setAttribute('data-next-page', newPage);

                        if (html.trim() === '') {
                            this.remove();
                        }
                    });
            });
        });
    </script>

    <script>
        const filteredStationNames = @json($stations->pluck('station_name'));
    </script>

@endsection
