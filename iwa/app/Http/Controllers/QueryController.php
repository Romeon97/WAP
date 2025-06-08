<?php
namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Query;
use Illuminate\Http\Request;

class QueryController extends Controller
{
    public function index(Contract $contract)
    {
        //Alle queries voor dit contract
        $queries = $contract->queries()->orderBy('id')->get();

        return view('queries.index', compact('contract', 'queries'));
    }

    public function create(Contract $contract)
    {
        //Form voor nieuwe query
        return view('queries.create', compact('contract'));
    }

    public function store(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'omschrijving' => 'required|string|max:255',
        ]);

        $validated['contract_id'] = $contract->id;
        Query::create($validated);

        return redirect()
            ->route('contracts.queries.index', $contract)
            ->with('success', 'Nieuwe query is succesvol aangemaakt.');
    }

    public function show(Contract $contract, Query $query)
    {
        if ($query->contract_id !== $contract->id) {
            abort(404);
        }

        return view('queries.show', compact('contract', 'query'));
    }

    public function edit(Contract $contract, Query $query)
    {
        if ($query->contract_id !== $contract->id) {
            abort(404);
        }

        return view('queries.edit', compact('contract', 'query'));
    }

    public function update(Request $request, Contract $contract, Query $query)
    {
        if ($query->contract_id !== $contract->id) {
            abort(404);
        }

        $validated = $request->validate([
            'omschrijving' => 'required|string|max:255',
        ]);

        $query->update($validated);

        return redirect()
            ->route('contracts.queries.index', $contract)
            ->with('success', 'Query is succesvol bijgewerkt.');
    }

    public function destroy(Contract $contract, Query $query)
    {
        if ($query->contract_id !== $contract->id) {
            abort(404);
        }

        $query->delete();

        return redirect()
            ->route('contracts.queries.index', $contract)
            ->with('success', 'Query is succesvol verwijderd.');
    }
}
