<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Query;
use App\Models\CriteriumGroup;
use App\Models\Criterium;
use Illuminate\Http\Request;

class CriteriumController extends Controller
{
    public function index(Contract $contract, Query $query, CriteriumGroup $group)
    {
        //Check dat alles bij elkaar hoort
        if ($query->contract_id !== $contract->id || $group->query !== $query->id) {
            abort(404);
        }

        //Haal alle criteria op die bij deze group horen
        $criteria = $group->criteria()->orderBy('id')->get();

        return view('criteria.index', compact('contract', 'query', 'group', 'criteria'));
    }

    public function create(Contract $contract, Query $query, CriteriumGroup $group)
    {
        if ($query->contract_id !== $contract->id || $group->query !== $query->id) {
            abort(404);
        }

        //Toon formulier om nieuw criterium aan te maken
        return view('criteria.create', compact('contract', 'query', 'group'));
    }

    public function store(Request $request, Contract $contract, Query $query, CriteriumGroup $group)
    {
        if ($query->contract_id !== $contract->id || $group->query !== $query->id) {
            abort(404);
        }

        $validated = $request->validate([
            // 'operator' => 'required|integer',
            // 'int_value' => 'nullable|integer',
            // 'string_value' => 'nullable|string',
            // 'float_value' => 'nullable|numeric',
            // 'value_type' => 'required|integer',
            // 'comparison' => 'required|integer',
        ]);

        // Koppel dit criterium aan de group
        $validated['group'] = $group->id;

        Criterium::create($validated);

        return redirect()
            ->route('contracts.queries.groups.criteria.index', [$contract, $query, $group])
            ->with('success', 'Criterium succesvol aangemaakt.');
    }

    public function show(Contract $contract, Query $query, CriteriumGroup $group, Criterium $criterium)
    {
        //Check dat $criterium bij $group hoort, en $group bij $query, enz.
        if ($query->contract_id !== $contract->id || $group->query !== $query->id || $criterium->group !== $group->id) {
            abort(404);
        }

        return view('criteria.show', compact('contract', 'query', 'group', 'criterium'));
    }

    public function edit(Contract $contract, Query $query, CriteriumGroup $group, Criterium $criterium)
    {
        if ($query->contract_id !== $contract->id || $group->query !== $query->id || $criterium->group !== $group->id) {
            abort(404);
        }

        return view('criteria.edit', compact('contract', 'query', 'group', 'criterium'));
    }

    public function update(Request $request, Contract $contract, Query $query, CriteriumGroup $group, Criterium $criterium)
    {
        if ($query->contract_id !== $contract->id || $group->query !== $query->id || $criterium->group !== $group->id) {
            abort(404);
        }

        $validated = $request->validate([
            // ...
        ]);

        $criterium->update($validated);

        return redirect()
            ->route('contracts.queries.groups.criteria.index', [$contract, $query, $group])
            ->with('success', 'Criterium succesvol bijgewerkt.');
    }

    public function destroy(Contract $contract, Query $query, CriteriumGroup $group, Criterium $criterium)
    {
        if ($query->contract_id !== $contract->id || $group->query !== $query->id || $criterium->group !== $group->id) {
            abort(404);
        }

        $criterium->delete();

        return redirect()
            ->route('contracts.queries.groups.criteria.index', [$contract, $query, $group])
            ->with('success', 'Criterium succesvol verwijderd.');
    }
}
