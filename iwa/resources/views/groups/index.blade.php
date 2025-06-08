@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Criterium Groups voor Query: {{ $query->omschrijving }}</h1>

        <a href="{{ route('contracts.queries.groups.create', [$contract, $query]) }}"
           class="btn btn-primary mb-3">
            Nieuwe Group aanmaken
        </a>

        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Level</th>
                <th>Operator</th>
                <th>Acties</th>
            </tr>
            </thead>
            <tbody>
            @foreach($groups as $grp)
                <tr>
                    <td>{{ $grp->id }}</td>
                    <td>{{ $grp->type }}</td>
                    <td>{{ $grp->group_level }}</td>
                    <td>{{ $grp->operator }}</td>
                    <td>
                        <a href="{{ route('contracts.queries.groups.criteria.index', [$contract, $query, $grp]) }}"
                           class="btn btn-sm btn-info">
                            Criteria
                        </a>

                        <a href="{{ route('contracts.queries.groups.edit', [$contract, $query, $grp]) }}"
                           class="btn btn-sm btn-warning">
                            Bewerken
                        </a>

                        <form action="{{ route('contracts.queries.groups.destroy', [$contract, $query, $grp]) }}"
                              method="POST"
                              style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Weet je zeker dat je deze group wilt verwijderen?')">
                                Verwijderen
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
