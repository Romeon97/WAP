@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="page-title">🚨 Errors for this station {{ $station->station_name }}</h1>
        <p style="font-size: 1.3rem; font-weight: 600; margin-bottom: 20px;">
            Country: <span style="color: #333;">{{ $station->country_name }}</span>
        </p>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="user-table">
            <thead>
            <tr>
                <th>Error type</th>
                <th>Date</th>
                <th>Received value / Missing field</th>
                <th>Corrected value</th>
                <th>Note</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($errors as $error)
                <tr style="{{ $error->status === 'resolved' ? 'background-color: #D1FFBD; color: #666; font-style: italic;' : '' }}">
                    <td>
                        @if (!is_null($error->invalid_temperature))
                            invalid temperature
                        @elseif (!is_null($error->missing_field))
                            missing field
                        @endif
                    </td>

                    <td>{{ $error->date }} {{ $error->time }}</td>

                    <td>
                        @if (!is_null($error->invalid_temperature))
                            {{ $error->invalid_temperature }} °C
                        @elseif (!is_null($error->missing_field))
                            {{ $error->missing_field }}
                        @else
                            -
                        @endif
                    </td>

                    <td>
                        @if (!is_null($error->invalid_temperature))
                            {{ $error->temperature }} °C
                        @elseif (!is_null($error->missing_field))
                            @php
                                $field = $error->missing_field;
                                echo $error->$field ?? 'Unknown';
                            @endphp
                        @else
                            -
                        @endif
                    </td>

                    <form method="POST" action="{{ route('monitoring.errors.update', $error->id) }}">
                        @csrf
                        @method('PUT')

                        <td>
                            <input type="text" name="note" value="{{ $error->note }}" class="form-control w-full">
                        </td>

                        <td>
                            <select name="status" class="form-control w-full">
                                <option value="active" {{ $error->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="resolved" {{ $error->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            </select>
                        </td>

                        <td class="action-buttons">
                            <button class="save-btn" type="submit">Save</button>
                        </td>
                    </form>
                </tr>
            @endforeach
            </tbody>


        </table>
    </div>
@endsection
