<?php

namespace App\Services;

use App\Models\Measurement;
use App\Models\OriginalMeasurement;
use Illuminate\Support\Facades\Log;

class WeatherDataProcessor
{
    public function process(array $station): void
    {
        // Debuglog: volledige invoer als één string
        Log::info(implode(' ', $station));

        $stationId = (string) $station['STN'];
        $date = $station['DATE'];
        $time = $station['TIME'];

        // Temperatuur uit invoer ophalen
        $rawTemp = $this->toKelvin($station['TEMP'] === 'None' ? null : $station['TEMP']);
        $correctedTemp = $rawTemp;

        // Laatste 30 temperatuurmetingen ophalen voor station
        $lastTemperatures = Measurement::where('station', $stationId)
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->limit(30)
            ->pluck('temperature')
            ->filter()
            ->toArray();

        // Als er minder dan 30 metingen zijn -> posten op database zonder extrapolatie
        if (count($lastTemperatures) < 30) {
            Log::info("Minder dan 30 metingen voor station {$stationId} op {$date} {$time} — data wordt direct opgeslagen zonder correcties.");

            $measurement = Measurement::create([
                'station' => $stationId,
                'date' => $date,
                'time' => $time,
                'temperature' => $this->toCelsius($rawTemp),
                'dewpoint_temperature' => $station['DEWP'] === 'None' ? null : $station['DEWP'],
                'air_pressure_station' => $station['STP'] === 'None' ? null : $station['STP'],
                'air_pressure_sea_level' => $station['SLP'] === 'None' ? null : $station['SLP'],
                'visibility' => $station['VISIB'] === 'None' ? null : $station['VISIB'],
                'wind_speed' => $station['WDSP'] === 'None' ? null : $station['WDSP'],
                'percipation' => $station['PRCP'] === 'None' ? null : $station['PRCP'],
                'snow_depth' => $station['SNDP'] === 'None' ? null : $station['SNDP'],
                'conditions' => $station['FRSHTT'] === 'None' ? null : $station['FRSHTT'],
                'cloud_cover' => $station['CLDC'] === 'None' ? null : $station['CLDC'],
                'wind_direction' => $station['WNDDIR'] === 'None' ? null : $station['WNDDIR'],
            ]);

            foreach ([
                     'dewpoint_temperature' => 'DEWP',
                     'air_pressure_station' => 'STP',
                     'air_pressure_sea_level' => 'SLP',
                     'visibility' => 'VISIB',
                     'wind_speed' => 'WDSP',
                     'percipation' => 'PRCP',
                     'snow_depth' => 'SNDP',
                     'conditions'=> 'FRSHTT',
                     'cloud_cover' => 'CLDC',
                     'wind_direction' => 'WNDDIR',
                     ] as $field => $jsonKey) {
                if ($station[$jsonKey] === 'None') {
                    OriginalMeasurement::create([
                        'corrected_measurement' => $measurement->id,
                        'missing_field' => $field,
                        'invalid_temperature' => null,
                    ]);

                    Log::info("Ontbrekend veld '{$field}' bij station {$stationId} op {$date} {$time} (niet ingevuld wegens <30 metingen)");
                }
            }

            return;
        }

        // Elke temperatuur om naar Kelvin
        $temperaturesInKelvin = array_map([$this, 'toKelvin'], $lastTemperatures);

        // Gemiddelde temperatuur berekenen
        $sum = array_sum($temperaturesInKelvin);
        $estimatedTemp = $sum / 30;

        $correctionNeeded = false;
        $missingTemp = false;
        $invalidTemp = null;

        $twentyPercentOfRawTemp = $rawTemp * 0.2;
        $lowerBound = $estimatedTemp - $twentyPercentOfRawTemp;
        $upperBound = $estimatedTemp + $twentyPercentOfRawTemp;

        if ($rawTemp < $lowerBound) {
            $invalidTemp = $this->toCelsius($rawTemp);
            $correctedTemp = $lowerBound;
            $correctionNeeded = true;

            Log::info("Onrealistische lage temperatuur bij station {$stationId} op {$date} {$time}. Ingekomen: {$rawTemp}, verwachting: {$estimatedTemp}, aangepast naar: {$correctedTemp}");
        }

        if ($rawTemp > $upperBound) {
            $invalidTemp = $this->toCelsius($rawTemp);
            $correctedTemp = $upperBound;
            $correctionNeeded = true;

            Log::info("Onrealistische hoge temperatuur bij station {$stationId} op {$date} {$time}. Ingekomen: {$rawTemp}, verwachting: {$estimatedTemp}, aangepast naar: {$correctedTemp}");
        }

        // Meting samenstellen en lege waardes vervangen met de vorige data
        $measurementData = [
            'station' => $stationId,
            'date' => $date,
            'time' => $time,
            'temperature' => $this->toCelsius($correctedTemp),
            'dewpoint_temperature' => $station['DEWP'] === 'None'
                ? $this->getExtrapolationValue($stationId, 'dewpoint_temperature')
                : $station['DEWP'],
            'air_pressure_station' => $station['STP'] === 'None'
                ? $this->getExtrapolationValue($stationId, 'air_pressure_station')
                : $station['STP'],
            'air_pressure_sea_level' => $station['SLP'] === 'None'
                ? $this->getExtrapolationValue($stationId, 'air_pressure_sea_level')
                : $station['SLP'],
            'visibility' => $station['VISIB'] === 'None'
                ? $this->getExtrapolationValue($stationId, 'visibility')
                : $station['VISIB'],
            'wind_speed' => $station['WDSP'] === 'None'
                ? $this->getExtrapolationValue($stationId, 'wind_speed')
                : $station['WDSP'],
            'percipation' => $station['PRCP'] === 'None'
                ? $this->getExtrapolationValue($stationId, 'percipation')
                : $station['PRCP'],
            'snow_depth' => $station['SNDP'] === 'None'
                ? $this->getExtrapolationValue($stationId, 'snow_depth')
                : $station['SNDP'],
            'conditions'=> $station['FRSHTT'] === 'None'
                ? $this->getExtrapolationValue($stationId, 'conditions')
                : $station['FRSHTT'],
            'cloud_cover' => $station['CLDC'] === 'None'
                ? $this->getExtrapolationValue($stationId, 'cloud_cover')
                : $station['CLDC'],
            'wind_direction' => $station['WNDDIR'] === 'None'
                ? $this->getExtrapolationValue($stationId, 'wind_direction')
                : $station['WNDDIR'],
        ];

        // Nieuwe meting opslaan
        $measurement = Measurement::create($measurementData);

        // Als er een correctie op temperatuur nodig is opslaan in orginele meting tabel
        if ($correctionNeeded) {
            OriginalMeasurement::create([
                'corrected_measurement' => $measurement->id,
                'missing_field' => $missingTemp ? 'temperature' : null,
                'invalid_temperature' => $invalidTemp,
            ]);
        }

        // Overige velden controleren op ontbrekende waarden
        $fieldsToCheck = [
            'dewpoint_temperature' => 'DEWP',
            'air_pressure_station' => 'STP',
            'air_pressure_sea_level' => 'SLP',
            'visibility' => 'VISIB',
            'wind_speed' => 'WDSP',
            'percipation' => 'PRCP',
            'snow_depth' => 'SNDP',
            'conditions'=> 'FRSHTT',
            'cloud_cover' => 'CLDC',
            'wind_direction' => 'WNDDIR',
        ];

        foreach ($fieldsToCheck as $field => $jsonKey) {
            if ($station[$jsonKey] === 'None') {
                OriginalMeasurement::create([
                    'corrected_measurement' => $measurement->id,
                    'missing_field' => $field,
                    'invalid_temperature' => null,
                ]);

                Log::info("Ontbrekend veld '{$field}' bij station {$stationId} op {$date} {$time}");
            }
        }
    }

    private function getExtrapolationValue(string $station, string $field): float|int|string
    {
        Log::info("Extrapolatie starten voor '{$field}' bij station {$station}");

        $values = Measurement::where('station', $station)
            ->whereNotNull($field)
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->limit(30)
            ->pluck($field)
            ->toArray();

        // Velden die als string (categorisch) behandeld moeten worden
        $stringFields = ['conditions', 'wind_direction'];

        if (in_array($field, $stringFields)) {
            $counts = array_count_values($values);
            arsort($counts);
            $mode = array_key_first($counts);

            Log::info("Modus voor '{$field}' is '{$mode}'");
            return $mode;
        }

        // Anders: numeriek veld -> gemiddelde
        $sum = array_sum($values);
        $avg = round($sum / count($values), 1);

        Log::info("Gemiddelde voor '{$field}' is {$avg}");
        return $avg;
    }

    function toKelvin(float $celsius): float {
        return $celsius + 273.15;
    }

    function toCelsius(float $kelvin): float {
        return $kelvin - 273.15;
    }
}
