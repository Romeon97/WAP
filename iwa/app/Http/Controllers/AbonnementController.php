<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbonnementController extends Controller
{
    public function showWeather($identifier, Request $request)
    {
        //Token uitlezen en subscription ophalen
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['message' => 'Unauthorized: Token missing'], 401);
        }

        //Subscription zoeken op basis van de token
        $subscription = DB::table('subscriptions')
            ->where('token', $token)
            ->first();

        if (!$subscription) {
            return response()->json(['message' => 'Unauthorized: Invalid token'], 401);
        }

        //Check of de identifier in de URL overeenkomt met de subscription->identifier
        if ($subscription->identifier !== $identifier) {
            return response()->json(['message' => 'Unauthorized: Identifier mismatch'], 403);
        }

        //Loggen in endpoint_activity
        DB::table('endpoint_activity')->insert([
            'identifier'       => $identifier,
            'endpoint_used'    => '/api/abonnement/'.$identifier,
            'files_downloaded' => 0,
            'activity_date'    => date('Y-m-d'),
            'activity_time'    => date('H:i:s'),
            'authorized'       => 1,
            'data_transferred' => 0,
        ]);

        //Alle stations behorend bij deze subscription
        $stations = DB::table('subscription_station')
            ->where('subscription', $subscription->id)
            ->pluck('station');  // bijv. ['STN01', 'STN02', ...]

        if ($stations->isEmpty()) {
            return response()->json([
                'identifier'   => $identifier,
                'weather_data' => 'Geen stations gevonden voor deze subscription',
            ]);
        }

        //Voor elk station de meest recente meting ophalen
        $weatherData = [];
        foreach ($stations as $stationName) {
            $latestMeasurement = DB::table('measurement')
                ->where('station', $stationName)
                ->orderBy('date', 'desc')
                ->orderBy('time', 'desc')
                ->first();

            if ($latestMeasurement) {
                $weatherData[] = [
                    'station'              => $stationName,
                    'date'                 => $latestMeasurement->date,
                    'time'                 => $latestMeasurement->time,
                    'temperature'          => $latestMeasurement->temperature,
                    'dewpoint_temperature' => $latestMeasurement->dewpoint_temperature,
                    'wind_speed'           => $latestMeasurement->wind_speed,
                    'conditions'           => $latestMeasurement->conditions,
                    'air_pressure_station' => $latestMeasurement->air_pressure_station,
                    'air_pressure_sea_level'     => $latestMeasurement->air_pressure_sea_level,
                    'visibility'           => $latestMeasurement->visibility,
                    'precipitation'        => $latestMeasurement->precipitation,
                    'snow_depth'           => $latestMeasurement->snow_depth,
                    'cloud_cover'          => $latestMeasurement->cloud_cover,
                    'wind_direction'       => $latestMeasurement->wind_direction,
                ];
            } else {
                //Als er geen meting is gevonden voor dit station een melding toevoegen:
                $weatherData[] = [
                    'station' => $stationName,
                    'message' => 'Geen metingen gevonden voor dit station'
                ];
            }
        }

        //Response
        $data = [
            'identifier'   => $identifier,
            'weather_data' => $weatherData,
        ];

        return response()->json($data);
    }

    public function listStations($identifier, Request $request)
    {
        //Token uitlezen
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['message' => 'Unauthorized: Token missing'], 401);
        }

        //Subscription zoeken
        $subscription = DB::table('subscriptions')
            ->where('token', $token)
            ->first();
        if (!$subscription) {
            return response()->json(['message' => 'Unauthorized: Invalid token'], 401);
        }

        //Identifier-check
        if ($subscription->identifier !== $identifier) {
            return response()->json(['message' => 'Unauthorized: Identifier mismatch'], 403);
        }

        //Loggen
        DB::table('endpoint_activity')->insert([
            'identifier'       => $identifier,
            'endpoint_used'    => '/api/abonnement/'.$identifier.'/stations',
            'files_downloaded' => 0,
            'activity_date'    => date('Y-m-d'),
            'activity_time'    => date('H:i:s'),
            'authorized'       => 1,
            'data_transferred' => 0,
        ]);

        //Stations ophalen
        $stations = DB::table('subscription_station')
            ->join('station', 'subscription_station.station', '=', 'station.name')
            ->where('subscription_station.subscription', $subscription->id)
            ->select('station.name', 'station.longitude', 'station.latitude', 'station.elevation')
            ->get();

        //Response
        return response()->json([
            'identifier' => $identifier,
            'stations'   => $stations,
        ]);
    }

    public function stationDetails($identifier, $naam, Request $request)
    {
        //Token uitlezen
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['message' => 'Unauthorized: Token missing'], 401);
        }

        //Subscription zoeken
        $subscription = DB::table('subscriptions')
            ->where('token', $token)
            ->first();
        if (!$subscription) {
            return response()->json(['message' => 'Unauthorized: Invalid token'], 401);
        }

        //Identifier-check
        if ($subscription->identifier !== $identifier) {
            return response()->json(['message' => 'Unauthorized: Identifier mismatch'], 403);
        }

        //Loggen
        DB::table('endpoint_activity')->insert([
            'identifier'       => $identifier,
            'endpoint_used'    => '/api/abonnement/'.$identifier.'/station/'.$naam,
            'files_downloaded' => 0,
            'activity_date'    => date('Y-m-d'),
            'activity_time'    => date('H:i:s'),
            'authorized'       => 1,
            'data_transferred' => 0,
        ]);

        //Check of station aan deze subscription gekoppeld is
        $hasStation = DB::table('subscription_station')
            ->where('subscription', $subscription->id)
            ->where('station', $naam)
            ->exists();

        if (!$hasStation) {
            return response()->json([
                'message' => 'Unauthorized: Dit station hoort niet bij deze subscription'
            ], 401);
        }

        //Haal gegevens uit nearestlocation
        $nearestLocation = DB::table('nearestlocation')
            ->where('station_name', $naam)
            ->first();

        if (!$nearestLocation) {
            //Fallback
            return response()->json([
                'identifier'       => $identifier,
                'station_name'     => $naam,
                'nearest_location' => 'Onbekend'
            ]);
        }

        //Response
        $data = [
            'identifier'       => $identifier,
            'station_name'     => $naam,
            'nearest_location' => $nearestLocation->name . ', ' . $nearestLocation->country_code,
            'longitude'        => $nearestLocation->longitude,
            'latitude'         => $nearestLocation->latitude,
        ];

        return response()->json($data);
    }
}
