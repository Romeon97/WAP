@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Criteria voor Group #{{ $group->id }} (Query: {{ $query->omschrijving }})</h1>

        <a href="{{ route('contracts.queries.groups.criteria.create', [$contract, $query, $group]) }}"
           class="btn btn-primary mb-3">
            Nieuw Criterium
        </a>

        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Operator</th>
                <th>Value</th>
                <th>Comparison</th>
                <th>Acties</th>
            </tr>
            </thead>
            <tbody>
            @foreach($criteria as $crit)
                <tr>
                    <td>{{ $crit->id }}</td>
                    <td>{{ $crit->operator }}</td>
                    <td>
                        @if($crit->value_type == 1)
                            {{ $crit->int_value }}
                        @elseif($crit->value_type == 2)
                            {{ $crit->string_value }}
                        @elseif($crit->value_type == 3)
                            {{ $crit->float_value }}
                        @endif
                    </td>
                    <td>{{ $crit->comparison }}</td>
                    <td>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
