<?php

namespace App\Http\Controllers;

use App\Models\NearestLocation;
use App\Models\Measurement;
use App\Models\Country;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NearestLocationController extends Controller
{
    public function index(Request $request)
    {
        $filterType = $request->get('filter_type', 'region');
        $filterValue = $request->get('filter_value');

        $query = \DB::table('nearestlocation');

        if ($filterType === 'region' && $filterValue) {
            $query->where('nearestlocation.administrative_region1', $filterValue);
        } elseif ($filterType === 'country' && $filterValue) {
            $country = Country::where('country', $filterValue)->first();
            if ($country) {
                $query->where('nearestlocation.country_code', $country->country_code);
            }
        }

        $search = $request->get('search');

        if ($search) {
            $query->where('nearestlocation.station_name', 'like', '%' . $search . '%');
        }

        $query = $query
            ->join('country', 'nearestlocation.country_code', '=', 'country.country_code')
            ->select('nearestlocation.*', 'country.country as country_name');

        if ($filterValue || $search) {
            $stations = $query->orderBy('nearestlocation.station_name')->get();
        } else {
            $stations = $query->orderBy('nearestlocation.station_name')->paginate(44);
        }

        $regions = NearestLocation::select('administrative_region1')
            ->whereNotNull('administrative_region1')
            ->where('administrative_region1', '!=', '')
            ->distinct()
            ->orderBy('administrative_region1')
            ->pluck('administrative_region1');

        $countries = Country::orderBy('country')->pluck('country');

        return view('nearestlocationView', compact('stations', 'filterType', 'filterValue', 'regions', 'countries', 'search'));
    }


    public function showMeasurements($stationName)
    {
        $station = NearestLocation::where('station_name', $stationName)->firstOrFail();

        $measurementFilter = request('measurement');
        $startDate = request('start_date');
        $endDate = request('end_date');

        $query = Measurement::where('station', $stationName);

        if ($startDate) {
            $query->whereDate('date', '>=', Carbon::parse($startDate)->toDateString());
        }
        if ($endDate) {
            $query->whereDate('date', '<=', Carbon::parse($endDate)->toDateString());
        }

        $measurements = $query
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->get();

        $chartLabels = [];
        $chartData = [];

        if ($measurementFilter) {
            foreach ($measurements as $m) {
                $label = $m->date . ' ' . $m->time;
                $value = $m->{$measurementFilter};

                if (!is_null($value)) {
                    $chartLabels[] = $label;
                    $chartData[] = $value;
                }
            }
        }

        return view('stationMeasurements', compact('station', 'measurements', 'measurementFilter', 'startDate', 'endDate', 'chartLabels', 'chartData'));
    }

    public function loadMore(Request $request)
    {
        $page = $request->get('page', 1);

        // alleen ophalen via AJAX
        if ($request->ajax()) {
            $stations = \DB::table('nearestlocation')
                ->join('country', 'nearestlocation.country_code', '=', 'country.country_code')
                ->select('nearestlocation.*', 'country.country as country_name')
                ->orderBy('nearestlocation.station_name')
                ->paginate(24, ['*'], 'page', $page);
            return view('partials.station-cards', compact('stations'))->render();
        }

        abort(404); // als iemand 't faked
    }

    public function mapData()
    {
        $stations = NearestLocation::select('station_name', 'country_code', 'latitude', 'longitude')->get();
        return response()->json($stations);
    }

}
