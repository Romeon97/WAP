@extends('layouts.app')

@section('content')
    <div class="container text-center">
        <h1 class="page-title">📡 Station monitoring</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" style="display: flex; justify-content: center; gap: 30px; margin-top: 40px; flex-wrap: wrap;">
            <!-- All stations -->
            <div class="dashboard-card" style="background: white; padding: 25px 40px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-top: 5px solid #2563EB; min-width: 280px;">
                <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 10px;">
                    🌍 All stations
                </h2>
                <div style="font-size: 3rem; font-weight: bold; color: #2563EB;">{{ $totalStations }}</div>
                <a href="{{ route('nearestlocation.index') }}" class="dashboard-link" style="display: inline-block; margin-top: 15px; background: #2563EB; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none;">
                    View all →
                </a>
            </div>
            <!-- Active stations -->
            <div class="dashboard-card" style="background: white; padding: 25px 40px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-top: 5px solid green; min-width: 280px;">
                <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 10px;">
                    🟢 Active stations
                </h2>
                <div style="font-size: 3rem; font-weight: bold; color: green;">{{ $activeCount }}</div>
                <a href="{{ route('monitoring.active') }}" class="dashboard-link" style="display: inline-block; margin-top: 15px; background: green; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none;">
                    View stations →
                </a>
            </div>

            <!-- Active errors -->
            <div class="dashboard-card" style="background: white; padding: 25px 40px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-top: 5px solid red; min-width: 280px;">
                <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 10px;">
                    🚨 Active errors
                </h2>
                <div style="font-size: 3rem; font-weight: bold; color: red;">{{ $errorCount }}</div>
                <a href="{{ route('monitoring.errors') }}" class="dashboard-link" style="display: inline-block; margin-top: 15px; background: red; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none;">
                    View errors →
                </a>
            </div>
        </div>
    </div>





    <div class="mt-10 bg-white shadow-md rounded-xl p-6">
        <h2 class="text-xl font-semibold mb-4">📈 Activity past 14 days</h2>
        <canvas id="activeTrendChart" height="70"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('activeTrendChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($dates) !!},
                datasets: [
                    {
                        label: 'Active stations',
                        data: {!! json_encode($activeCounts) !!},
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        fill: true,
                        tension: 0,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        spanGaps: false
                    },
                    {
                        label: 'Active errors',
                        data: {!! json_encode($errorCounts) !!},
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.1)',
                        fill: true,
                        tension: 0,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        spanGaps: false
                    }
                ]

            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    </script>
@endsection

