@extends('layouts.app')

@section('content')
    <div class="container bedrijf-form">
        <h1>New company</h1>

        <form action="{{ route('bedrijven.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Company name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="street" class="form-label">Street</label>
                <input type="text" name="street" class="form-control">
            </div>

            <div class="mb-3">
                <label for="number" class="form-label">Number</label>
                <input type="text" name="number" class="form-control">
            </div>

            <div class="mb-3">
                <label for="zip_code" class="form-label">Zip code</label>
                <input type="text" name="zip_code" class="form-control">
            </div>

            <div class="mb-3">
                <label for="city" class="form-label">City</label>
                <input type="text" name="city" class="form-control">
            </div>

            <div class="mb-3">
                <label for="country" class="form-label">Country</label>
                <input type="text" name="country" class="form-control">
            </div>


            <div class="mb-3">
                <label for="email" class="form-label">Contact Email</label>
                <input type="email" name="email" class="form-control">
            </div>


            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
@endsection
