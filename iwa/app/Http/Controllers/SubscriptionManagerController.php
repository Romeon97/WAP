<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Subscription;
use App\Models\SubscriptionType;
use App\Models\Company;
use App\Models\Station;

class SubscriptionManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!in_array(Auth::user()->user_role, [3, 6])) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $selectedType = $request->input('type');
        $query = Subscription::with(['companyRelation', 'subscriptionType']);

        if ($selectedType) {
            $query->where('type', $selectedType);
        }

        $subscriptions = $query->get();
        $types = SubscriptionType::all();
        $companies = Company::all();

        return view('subscriptionManagerView', compact('subscriptions', 'types', 'selectedType', 'companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company'    => 'required|integer',
            'type'       => 'required|integer',
            'start_date' => 'required|date',
            'end_date'   => 'nullable|date',
            'price'      => 'required|numeric',
            'identifier' => 'required|string|max:45|unique:subscriptions,identifier',
            'notes'      => 'nullable|string',
        ]);

        $validated['token'] = Str::random(60);
        Subscription::create($validated);

        return redirect()->route('manage-subscriptions.index')->with('success', 'Abonnement toegevoegd');
    }

    public function edit($id)
    {
        $subscription = Subscription::findOrFail($id);
        $types = SubscriptionType::all();
        $companies = Company::all();

        return view('subscriptionEdit', compact('subscription', 'types', 'companies'));
    }

    public function update(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);

        $validated = $request->validate([
            'company'    => 'required|integer',
            'type'       => 'required|integer',
            'start_date' => 'required|date',
            'end_date'   => 'nullable|date',
            'price'      => 'required|numeric',
            'identifier' => 'required|string|max:45|unique:subscriptions,identifier,' . $subscription->id,
            'notes'      => 'nullable|string',
        ]);

        $subscription->update($validated);
        return redirect()->route('manage-subscriptions.index')->with('success', 'Abonnement bijgewerkt');
    }

    public function destroy($id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->delete();
        return redirect()->route('manage-subscriptions.index')->with('success', 'Abonnement verwijderd');
    }

    public function editStations($id)
    {
        $subscription = Subscription::with(['companyRelation', 'subscriptionType'])->findOrFail($id);

        //Alle stations ophalen
        $allStations = Station::all();

        //Reeds gekoppelde stations
        $linkedStations = DB::table('subscription_station')
            ->where('subscription', $subscription->id)
            ->pluck('station')
            ->toArray();

        //View tonen
        return view('subscriptionManageStations', [
            'subscription'   => $subscription,
            'allStations'    => $allStations,
            'linkedStations' => $linkedStations,
        ]);
    }

    public function updateStations(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);

        $selectedStations = $request->input('stations', []);

        //Oude koppelingen verwijderen
        DB::table('subscription_station')
            ->where('subscription', $subscription->id)
            ->delete();

        //Nieuwe koppelingen opslaan
        foreach ($selectedStations as $stationName) {
            DB::table('subscription_station')->insert([
                'subscription' => $subscription->id,
                'station'      => $stationName,
            ]);
        }

        return redirect()->route('manage-subscriptions.index')
            ->with('success', 'Stations succesvol bijgewerkt');
    }
}
