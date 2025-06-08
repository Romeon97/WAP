@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="page-title">Companies</h1>

        <a href="{{ route('bedrijven.create') }}" class="register-button">Create new company</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Filter op landcode -->
        <form method="GET" action="{{ route('bedrijven.index') }}" style="margin: 20px 0;">
            <label for="country">Filter country:</label>
            <select name="country" id="country">
                <option value="">All countries</option>
                @foreach($countries as $country)
                    <option value="{{ $country }}"
                        {{ (isset($selectedCountry) && $selectedCountry == $country) ? 'selected' : '' }}>
                        {{ $country }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="map-btn">Filter</button>
        </form>

        <table class="user-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Street</th>
                <th>Number</th>
                <th>Zip code</th>
                <th>City</th>
                <th>Country</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($companies as $company)
                <tr>
                    <td>
                        <form method="POST" action="{{ route('bedrijven.update', $company->id) }}">
                            @csrf
                            @method('PUT')
                            <input type="text" name="name" value="{{ $company->name }}">
                    </td>
                    <td><input type="text" name="street" value="{{ $company->street }}"></td>
                    <td><input type="text" name="number" value="{{ $company->number }}"></td>
                    <td><input type="text" name="zip_code" value="{{ $company->zip_code }}"></td>
                    <td><input type="text" name="city" value="{{ $company->city }}"></td>
                    <td><input type="text" name="country" value="{{ $company->country }}"></td>
                    <td><input type="email" name="email" value="{{ $company->email }}"></td>
                    <td class="action-buttons">
                        <button type="submit" class="save-btn">Save</button>
                        </form>

                        <form action="{{ route('bedrijven.destroy', $company->id) }}" method="POST" style="margin-top: 5px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn" onclick="return confirm('Confirm delete')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
