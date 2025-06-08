@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="page-title">All Companies</h1>
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
            </tr>
            </thead>
            <tbody>
            @foreach($companies as $company)
                <tr>
                    <td>{{ $company->name }}</td>
                    <td>{{ $company->street }}</td>
                    <td>{{ $company->number }}</td>
                    <td>{{ $company->zip_code }}</td>
                    <td>{{ $company->city }}</td>
                    <td>{{ $company->country }}</td>
                    <td>{{ $company->email }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
