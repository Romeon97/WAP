@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="page-title">Measurements for this station: {{ $station->station_name }}</h2>

        <form method="GET" class="flex flex-wrap items-center gap-4 mb-6">
            <div>
                <label for="start_date">From:</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="border px-2 py-1 rounded" onchange="this.form.submit()">
            </div>

            <div>
                <label for="end_date">To:</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="border px-2 py-1 rounded" onchange="this.form.submit()">
            </div>
        </form>

        @if ($measurements->isEmpty())
            <p>No data found for this station.</p>
        @else
            <form method="GET" class="flex flex-wrap items-center gap-4 mb-6">
                <div>
                    <label for="measurement">Filter by measurement:</label>
                    <select name="measurement" id="measurement" onchange="this.form.submit()" class="border px-2 py-1 rounded">
                        <option value="">-- All --</option>
                        <option value="temperature" {{ $measurementFilter == 'temperature' ? 'selected' : '' }}>Temperature (°C)</option>
                        <option value="dewpoint_temperature" {{ $measurementFilter == 'dewpoint_temperature' ? 'selected' : '' }}>Dewpoint (°C)</option>
                        <option value="air_pressure_station" {{ $measurementFilter == 'air_pressure_station' ? 'selected' : '' }}>Air pressure (station)</option>
                        <option value="air_pressure_sea_level" {{ $measurementFilter == 'air_pressure_sea_level' ? 'selected' : '' }}>Air pressure (sea level)</option>
                        <option value="visibility" {{ $measurementFilter == 'visibility' ? 'selected' : '' }}>Visibility (km)</option>
                        <option value="wind_speed" {{ $measurementFilter == 'wind_speed' ? 'selected' : '' }}>Wind speed (km/h)</option>
                        <option value="percipation" {{ $measurementFilter == 'percipation' ? 'selected' : '' }}>Rainfall (mm)</option>
                        <option value="snow_depth" {{ $measurementFilter == 'snow_depth' ? 'selected' : '' }}>Snow depth (cm)</option>
                        <option value="conditions" {{ $measurementFilter == 'conditions' ? 'selected' : '' }}>Conditions</option>
                        <option value="cloud_cover" {{ $measurementFilter == 'cloud_cover' ? 'selected' : '' }}>Cloud cover (%)</option>
                        <option value="wind_direction" {{ $measurementFilter == 'wind_direction' ? 'selected' : '' }}>Wind direction (°)</option>
                    </select>
                </div>


            </form>


            <div class="table-responsive">
                <table class="user-table">
                    <thead>
                    <tr>
                        <th>Datum</th>
                        <th>Tijd</th>
                        @if (!$measurementFilter || $measurementFilter === 'temperature') <th>Temp (°C)</th> @endif
                        @if (!$measurementFilter || $measurementFilter === 'dewpoint_temperature') <th>Dewpoint (°C)</th> @endif
                        @if (!$measurementFilter || $measurementFilter === 'air_pressure_station') <th>Air pressure (station)</th> @endif
                        @if (!$measurementFilter || $measurementFilter === 'air_pressure_sea_level') <th>Air pressure (sea level)</th> @endif
                        @if (!$measurementFilter || $measurementFilter === 'visibility') <th>Visibility (km)</th> @endif
                        @if (!$measurementFilter || $measurementFilter === 'wind_speed') <th>Wind speed (km/h)</th> @endif
                        @if (!$measurementFilter || $measurementFilter === 'percipation') <th>Rainfall (mm)</th> @endif
                        @if (!$measurementFilter || $measurementFilter === 'snow_depth') <th>Snow depth (cm)</th> @endif
                        @if (!$measurementFilter || $measurementFilter === 'conditions') <th>Conditions</th> @endif
                        @if (!$measurementFilter || $measurementFilter === 'cloud_cover') <th>Cloud cover (%)</th> @endif
                        @if (!$measurementFilter || $measurementFilter === 'wind_direction') <th>Wind direction (°)</th> @endif
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($measurements as $m)
                        <tr>
                            <td>{{ $m->date }}</td>
                            <td>{{ $m->time }}</td>
                            @if (!$measurementFilter || $measurementFilter === 'temperature') <td>{{ $m->temperature }}</td> @endif
                            @if (!$measurementFilter || $measurementFilter === 'dewpoint_temperature') <td>{{ $m->dewpoint_temperature }}</td> @endif
                            @if (!$measurementFilter || $measurementFilter === 'air_pressure_station') <td>{{ $m->air_pressure_station }}</td> @endif
                            @if (!$measurementFilter || $measurementFilter === 'air_pressure_sea_level') <td>{{ $m->air_pressure_sea_level }}</td> @endif
                            @if (!$measurementFilter || $measurementFilter === 'visibility') <td>{{ $m->visibility }}</td> @endif
                            @if (!$measurementFilter || $measurementFilter === 'wind_speed') <td>{{ $m->wind_speed }}</td> @endif
                            @if (!$measurementFilter || $measurementFilter === 'percipation') <td>{{ $m->percipation }}</td> @endif
                            @if (!$measurementFilter || $measurementFilter === 'snow_depth') <td>{{ $m->snow_depth }}</td> @endif
                            @if (!$measurementFilter || $measurementFilter === 'conditions') <td>{{ $m->conditions }}</td> @endif
                            @if (!$measurementFilter || $measurementFilter === 'cloud_cover') <td>{{ $m->cloud_cover }}</td> @endif
                            @if (!$measurementFilter || $measurementFilter === 'wind_direction') <td>{{ $m->wind_direction }}</td> @endif
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>
            @if ($measurementFilter && count($chartLabels) > 0)
                <div class="mt-8 bg-white shadow-md rounded-xl p-6">
                    <h2 class="text-xl font-semibold mb-4"> {{ ucfirst(str_replace('_', ' ', $measurementFilter)) }} over time</h2>

                    <div style="height: 300px;">
                        <canvas id="stationChart" class="w-full h-full"></canvas>
                    </div>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const ctx = document.getElementById('stationChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: {!! json_encode($chartLabels) !!},
                                datasets: [{
                                    label: '{{ ucfirst(str_replace("_", " ", $measurementFilter)) }}',
                                    data: {!! json_encode($chartData) !!},
                                    borderColor: 'rgb(75, 192, 192)',
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    fill: true,
                                    tension: 0.3,
                                    pointRadius: 3,
                                    pointHoverRadius: 6
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: false,
                                        ticks: {
                                            precision: 1
                                        }
                                    }
                                }
                            }
                        });
                    });
                </script>

            @endif

        @endif
    </div>
@endsection
