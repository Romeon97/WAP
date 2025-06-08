<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function index()
    {
        $contracts = Contract::all();
        return view('contracts.index', compact('contracts'));
    }

    public function create()
    {
        return view('contracts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'identifier'   => 'required|string|max:255|unique:contract,identifier',
            'company_id'   => 'nullable|integer',
            'omschrijving' => 'nullable|string',
            'start_datum'  => 'nullable|date',
            'eind_datum'   => 'nullable|date',
            'url'          => 'nullable|url',
        ]);

        Contract::create($validated);

        return redirect()
            ->route('contracts.index')
            ->with('success', 'Contract is succesvol aangemaakt.');
    }

    public function show(Contract $contract)
    {
        return view('contracts.show', compact('contract'));
    }

    public function edit(Contract $contract)
    {
        return view('contracts.edit', compact('contract'));
    }

    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'identifier'   => "required|string|max:255|unique:contract,identifier,{$contract->id}",
            'company_id'   => 'nullable|integer',
            'omschrijving' => 'nullable|string',
            'start_datum'  => 'nullable|date',
            'eind_datum'   => 'nullable|date',
            'url'          => 'nullable|url',
        ]);

        $contract->update($validated);

        return redirect()
            ->route('contracts.index')
            ->with('success', 'Contract is succesvol bijgewerkt.');
    }

    public function destroy(Contract $contract)
    {
        $contract->delete();
        return redirect()
            ->route('contracts.index')
            ->with('success', 'Contract is succesvol verwijderd.');
    }
}
