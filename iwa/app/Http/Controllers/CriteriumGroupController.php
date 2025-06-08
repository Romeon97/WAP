<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Query;
use App\Models\CriteriumGroup;
use Illuminate\Http\Request;

class CriteriumGroupController extends Controller
{
    public function index(Contract $contract, Query $query)
    {
        //Controleer dat de query bij het contract hoort
        if ($query->contract_id !== $contract->id) {
            abort(404);
        }

        //Haal alle groups op die bij deze query horen
        $groups = $query->groups()->orderBy('id')->get();

        //Toon de view
        return view('groups.index', compact('contract', 'query', 'groups'));
    }

    public function create(Contract $contract, Query $query)
    {
        if ($query->contract_id !== $contract->id) {
            abort(404);
        }

        // Toon een formulier om een nieuwe group aan te maken
        return view('groups.create', compact('contract', 'query'));
    }

    public function store(Request $request, Contract $contract, Query $query)
    {
        if ($query->contract_id !== $contract->id) {
            abort(404);
        }

        // Valideer invoer
        $validated = $request->validate([
            'type' => 'required|integer', etc.
            'group_level' => 'required|integer',
            'operator' => 'required|integer',
        ]);

        // Koppel dit group-record aan de query
        $validated['query'] = $query->id;

        CriteriumGroup::create($validated);

        return redirect()
            ->route('contracts.queries.groups.index', [$contract, $query])
            ->with('success', 'Criterium Group succesvol aangemaakt.');
    }

    public function show(Contract $contract, Query $query, CriteriumGroup $group)
    {
        //Check of het group-record bij deze query en contract hoort
        if ($query->contract_id !== $contract->id || $group->query !== $query->id) {
            abort(404);
        }

        return view('groups.show', compact('contract', 'query', 'group'));
    }

    public function edit(Contract $contract, Query $query, CriteriumGroup $group)
    {
        if ($query->contract_id !== $contract->id || $group->query !== $query->id) {
            abort(404);
        }

        return view('groups.edit', compact('contract', 'query', 'group'));
    }

    public function update(Request $request, Contract $contract, Query $query, CriteriumGroup $group)
    {
        if ($query->contract_id !== $contract->id || $group->query !== $query->id) {
            abort(404);
        }

        $validated = $request->validate([
            // ...
        ]);

        $group->update($validated);

        return redirect()
            ->route('contracts.queries.groups.index', [$contract, $query])
            ->with('success', 'Criterium Group succesvol bijgewerkt.');
    }

    public function destroy(Contract $contract, Query $query, CriteriumGroup $group)
    {
        if ($query->contract_id !== $contract->id || $group->query !== $query->id) {
            abort(404);
        }

        $group->delete();

        return redirect()
            ->route('contracts.queries.groups.index', [$contract, $query])
            ->with('success', 'Criterium Group succesvol verwijderd.');
    }
}
