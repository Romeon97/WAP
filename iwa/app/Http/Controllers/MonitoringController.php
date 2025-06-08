<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\NearestLocation;

class MonitoringController extends Controller
{
    public function index()
    {
        $twoWeeksAgo = Carbon::now()->subDays(13);
        $dates = [];
        $activeCounts = [];
        $errorCounts = [];

        $totalStations = DB::table('nearestlocation')->count();

        for ($i = 0; $i < 14; $i++) {
            $currentDay = $twoWeeksAgo->copy()->addDays($i);
            $windowStart = $currentDay->copy()->subDays(13);

            // Actieve stations
            $stations = DB::table('measurement')
                ->select('station')
                ->whereBetween('date', [$windowStart->toDateString(), $currentDay->toDateString()])
                ->distinct()
                ->pluck('station');

            $activeCounts[] = $stations->count();

            // Actieve errors
            $activeErrorsOnDay = DB::table('original_measurement')
                ->join('measurement', 'original_measurement.corrected_measurement', '=', 'measurement.id')
                ->whereDate('measurement.date', '<=', $currentDay->toDateString())
                ->where(function ($query) use ($currentDay) {
                    $query->whereNull('original_measurement.resolved_at')
                        ->orWhereDate('original_measurement.resolved_at', '>', $currentDay->toDateString());
                })
                ->count();

            $errorCounts[] = $activeErrorsOnDay;
            $dates[] = $currentDay->toDateString();
        }

        $activeCount = end($activeCounts);
        $errorCount = DB::table('original_measurement')->where('status', 'active')->count();

        return view('monitoringStations', [
            'activeCount' => $activeCount,
            'errorCount' => $errorCount,
            'activeCounts' => $activeCounts,
            'errorCounts' => $errorCounts,
            'dates' => $dates,
            'totalStations' => $totalStations
        ]);
    }


    public function activeStations(Request $request)
    {
        $filterType = $request->get('filter_type', 'region');
        $filterValue = $request->get('filter_value');
        $search = $request->get('search');

        $twoWeeksAgo = now()->subDays(14);

        //haal de actieve stations op
        $activeStationNames = DB::table('measurement')
            ->whereDate('date', '>=', $twoWeeksAgo->toDateString())
            ->distinct('station')
            ->pluck('station');

        // maak query met join naar country
        $query = DB::table('nearestlocation')
            ->join('country', 'nearestlocation.country_code', '=', 'country.country_code')
            ->whereIn('nearestlocation.station_name', $activeStationNames)
            ->select('nearestlocation.*', 'country.country as country_name');

        // Filter op regio of land
        if ($filterType === 'region' && $filterValue) {
            $query->where('nearestlocation.administrative_region1', $filterValue);
        } elseif ($filterType === 'country' && $filterValue) {
            $query->where('country.country', $filterValue);
        }

        // Zoek op station
        if ($search) {
            $query->where('nearestlocation.station_name', 'like', '%' . $search . '%');
        }

        $stations = $query->get();

        // Regio- en landenlijsten ophalen voor filters
        $regions = DB::table('nearestlocation')
            ->select('administrative_region1')
            ->whereNotNull('administrative_region1')
            ->where('administrative_region1', '!=', '')
            ->distinct()
            ->orderBy('administrative_region1')
            ->pluck('administrative_region1');

        $countries = DB::table('country')
            ->orderBy('country')
            ->pluck('country');

        return view('activeStations', compact('stations', 'filterType', 'filterValue', 'search', 'regions', 'countries'));
    }


    public function activeErrors()
    {
        $activeErrorStations = DB::table('original_measurement')
            ->join('measurement', 'original_measurement.corrected_measurement', '=', 'measurement.id')
            ->join('nearestlocation', 'measurement.station', '=', 'nearestlocation.station_name')
            ->join('country', 'nearestlocation.country_code', '=', 'country.country_code')
            ->select(
                'nearestlocation.station_name',
                'country.country as country_name',
                'nearestlocation.latitude',
                'nearestlocation.longitude',
                DB::raw('count(*) as error_count')
            )
            ->where('original_measurement.status', 'active') // <--- deze zorgt dat resolved niet meetelt
            ->groupBy(
                'nearestlocation.station_name',
                'country.country',
                'nearestlocation.latitude',
                'nearestlocation.longitude'
            )
            ->get();

        return view('monitoringErrors', ['stations' => $activeErrorStations]);
    }

    public function showStationErrors($stationName)
    {
        $station = DB::table('nearestlocation')
            ->join('country', 'nearestlocation.country_code', '=', 'country.country_code')
            ->select('nearestlocation.*', 'country.country as country_name')
            ->where('nearestlocation.station_name', $stationName)
            ->first();

        $errors = DB::table('original_measurement')
            ->join('measurement', 'original_measurement.corrected_measurement', '=', 'measurement.id')
            ->where('measurement.station', $stationName)
            ->select(
                'original_measurement.*',
                'measurement.date',
                'measurement.time',
                'measurement.temperature',
                'measurement.dewpoint_temperature',
                'measurement.air_pressure_station',
                'measurement.air_pressure_sea_level',
                'measurement.visibility',
                'measurement.wind_speed',
                'measurement.percipation',
                'measurement.snow_depth',
                'measurement.conditions',
                'measurement.cloud_cover',
                'measurement.wind_direction'
            )
            ->orderBy('measurement.date', 'desc')
            ->orderBy('measurement.time', 'desc')
            ->get();

        return view('errorDetails', compact('station', 'errors'));
    }

    public function updateError(Request $request, $id)
    {
        $updateData = [
            'note' => $request->input('note'),
            'status' => $request->input('status')
        ];

        if ($request->input('status') === 'resolved') {
            $updateData['resolved_at'] = now();
        }

        DB::table('original_measurement')
            ->where('id', $id)
            ->update($updateData);

        return back()->with('success');
    }

}
