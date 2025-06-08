<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $selectedCountry = $request->input('country');

        $query = Company::query();

        if ($selectedCountry) {
            $query->where('country', $selectedCountry);
        }

        $companies = $query->get();
        $countries = Company::select('country')->distinct()->pluck('country')->filter()->values();

        return view('companies.index', compact('companies', 'selectedCountry', 'countries'));
    }


    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'street' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'number' => 'nullable|string|max:20',
        ]);

        Company::create($data);

        return redirect()->route('bedrijven.index')->with('success', 'Bedrijf toegevoegd!');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'street' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:20',
            'zip_code' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
        ]);

        $bedrijf = Company::findOrFail($id);
        $bedrijf->update($data);

        return redirect()->route('bedrijven.index')->with('success', 'Bedrijf bijgewerkt!');
    }

    public function destroy($id)
    {
        $bedrijf = Company::findOrFail($id);
        $bedrijf->delete();

        return redirect()->route('bedrijven.index')->with('success', 'Bedrijf verwijderd!');
    }





}
