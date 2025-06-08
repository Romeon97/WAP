@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="page-title">Endpoint Logs</h1>

        <!-- Filter formulier om te zoeken op een specifiek endpoint -->
        <form method="GET" action="{{ route('logs.index') }}" style="margin-bottom: 20px;">
            <label for="endpoint">Filter endpoint:</label>
            <select name="endpoint" id="endpoint">
                <option value="">All endpoints</option>
                @foreach($endpoints as $endpoint)
                    <option value="{{ $endpoint->endpoint_used }}"
                        {{ (isset($selectedEndpoint) && $selectedEndpoint == $endpoint->endpoint_used) ? 'selected' : '' }}>
                        {{ $endpoint->endpoint_used }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="map-btn">Filter</button>
        </form>

        <!-- Tabel met loggegevens -->
        <table class="user-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Identifier</th>
                <th>Endpoint Used</th>
                <th>Files Downloaded</th>
                <th>Activity Date</th>
                <th>Activity Time</th>
                <th>Authorized</th>
                <th>Data Transferred</th>
            </tr>
            </thead>
            <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $log->id }}</td>
                    <td>{{ $log->identifier }}</td>
                    <td>{{ $log->endpoint_used }}</td>
                    <td>{{ $log->files_downloaded }}</td>
                    <td>{{ $log->activity_date }}</td>
                    <td>{{ $log->activity_time }}</td>
                    <td>{{ $log->authorized }}</td>
                    <td>{{ $log->data_transferred }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Geen loggegevens gevonden.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
