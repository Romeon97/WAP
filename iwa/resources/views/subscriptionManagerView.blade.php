@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="page-title">Subcriptions</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Knop voor nieuw abonnement -->
        <button class="register-button" onclick="document.getElementById('addSubscriptionForm').style.display='block'">
            Add new subscription
        </button>

        <!-- Form voor nieuw abonnement -->
        <div id="addSubscriptionForm" class="bedrijf-form hidden">
            <h2>New subscription</h2>
            <form method="POST" action="{{ route('manage-subscriptions.store') }}">
                @csrf
                <div class="input-group">
                    <label>Company:</label>
                    <select name="company" required>
                        <option value="">-- Choose company --</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group">
                    <label>Type:</label>
                    <select name="type" required>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group">
                    <label>Start date:</label>
                    <input type="date" name="start_date" required>
                </div>
                <div class="input-group">
                    <label>End date:</label>
                    <input type="date" name="end_date">
                </div>
                <div class="input-group">
                    <label>Price:</label>
                    <input type="text" name="price" required>
                </div>
                <div class="input-group">
                    <label>Identifier:</label>
                    <input type="text" name="identifier" required>
                </div>
                <div class="input-group">
                    <label>Notes:</label>
                    <textarea name="notes"></textarea>
                </div>
                <div class="button-group">
                    <button class="register-button" type="submit">Save</button>
                    <button class="back-button" type="button" onclick="document.getElementById('addSubscriptionForm').style.display='none'">Cancel</button>
                </div>
            </form>
        </div>

        <!-- Filter -->
        <form method="GET" action="{{ route('manage-subscriptions.index') }}" style="margin-top: 30px;">
            <label for="type">Filter type:</label>
            <select name="type" id="type">
                <option value="">All types</option>
                @foreach($types as $type)
                    <option value="{{ $type->id }}" {{ (isset($selectedType) && $selectedType == $type->id) ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
            <button class="map-btn" type="submit">Filter</button>
        </form>

        <!-- Tabel met abonnementen -->
        <div class="table-striped">
            <table class="user-table">
                <thead>
                <tr>
                    <th>Company</th>
                    <th>Type</th>
                    <th>Start date</th>
                    <th>End date</th>
                    <th>Price</th>
                    <th>Identifier</th>
                    <th>Token</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($subscriptions as $subscription)
                    <tr>
                        <!-- Klikbaar via de bedrijfsnaam: -->
                        <td>
                            <a href="{{ route('manage-subscriptions.edit', $subscription->id) }}">
                                {{ $subscription->companyRelation->name ?? 'Onbekend' }}
                            </a>
                        </td>
                        <td>{{ $subscription->subscriptionType->name ?? 'Onbekend' }}</td>
                        <td>{{ $subscription->start_date }}</td>
                        <td>{{ $subscription->end_date }}</td>
                        <td>{{ $subscription->price }}</td>
                        <td>{{ $subscription->identifier }}</td>
                        <td>{{ $subscription->token }}</td>
                        <td>{{ $subscription->notes }}</td>
                        <td class="action-buttons">
                            <!-- Verwijderen -->
                            <form action="{{ route('manage-subscriptions.destroy', $subscription->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="delete-btn" type="submit" onclick="return confirm('Confirm delete?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function toggleEditForm(id) {
            var formRow = document.getElementById('edit-form-' + id);
            if (formRow) {
                formRow.classList.toggle('hidden');
            }
        }
    </script>
@endsection
