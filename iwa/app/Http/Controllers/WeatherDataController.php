<?php

namespace App\Http\Controllers;

use App\Services\WeatherDataProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Measurement;

//class WeatherDataController extends Controller
//{
//    public function receiveWeatherData(Request $request): \Illuminate\Http\JsonResponse
//    {
//        Log::info('Endpoint is geraakt');
//
//        $rawJson = $request->getContent();
//        $parsed = json_decode($rawJson, true);
//
//        if (isset($parsed['WEATHERDATA'])) {
//            foreach ($parsed['WEATHERDATA'] as $station) {
//                Measurement::create([
//                    'station' => (string) $station['STN'],
//                    'date' => $station['DATE'],
//                    'time' => $station['TIME'],
//                    'temperature' => $station['TEMP'] === 'None' ? null : $station['TEMP'],
//                    'dewpoint_temperature' => $station['DEWP'] === 'None' ? null : $station['DEWP'],
//                    'air_pressure_station' => $station['STP'] === 'None' ? null : $station['STP'],
//                    'air_pressure_sea_level' => $station['SLP'] === 'None' ? null : $station['SLP'],
//                    'visibility' => $station['VISIB'] === 'None' ? null : $station['VISIB'],
//                    'wind_speed' => $station['WDSP'] === 'None' ? null : $station['WDSP'],
//                    'percipation' => $station['PRCP'] === 'None' ? null : $station['PRCP'],
//                    'snow_depth' => $station['SNDP'] === 'None' ? null : $station['SNDP'],
//                    'conditions' => $station['FRSHTT'] ?? null,
//                    'cloud_cover' => $station['CLDC'] === 'None' ? null : $station['CLDC'],
//                    'wind_direction' => $station['WNDDIR'] === 'None' ? null : $station['WNDDIR'],
//                ]);
//            }
//
//            return response()->json(['status' => 'data opgeslagen']);
//        }
//
//        return response()->json(['status' => 'geen WEATHERDATA gevonden'], 400);
//    }
//}

class WeatherDataController extends Controller
{
    public function receiveWeatherData(Request $request): \Illuminate\Http\JsonResponse
    {
        Log::info('Endpoint is geraakt');
        $rawJson = $request->getContent();
        $parsed = json_decode($rawJson, true);

        if (isset($parsed['WEATHERDATA'])) {
            $processor = new WeatherDataProcessor();

            foreach ($parsed['WEATHERDATA'] as $station) {
                $processor->process($station);
            }

            return response()->json(['status' => 'data verwerkt en opgeslagen']);
        }

        return response()->json(['status' => 'geen WEATHERDATA gevonden'], 400);
    }
}
