<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractDataController extends Controller
{
    public function getQueryData($identifier, $queryID)
    {
        //Zoek contract op basis van identifier
        $contract = DB::table('contract')
            ->where('identifier', $identifier)
            ->first();

        if (!$contract) {
            return response()->json(['message' => 'Contract not found'], 404);
        }

        //Zoek de query bij dit contract
        $query = DB::table('query')
            ->where('id', $queryID)
            ->where('contract_id', $contract->id)
            ->first();

        if (!$query) {
            return response()->json(['message' => 'Query not found'], 404);
        }

        return response()->json([
            'contract_identifier' => $contract->identifier,
            'query'               => $query,
        ]);
    }
    public function getStationsForQuery($identifier, $queryID)
    {
        //Zoek contract op basis van identifier
        $contract = DB::table('contract')
            ->where('identifier', $identifier)
            ->first();

        if (!$contract) {
            return response()->json(['message' => 'Contract not found'], 404);
        }

        //Zoek de query
        $queryRow = DB::table('query')
            ->where('id', $queryID)
            ->where('contract_id', $contract->id)
            ->first();

        if (!$queryRow) {
            return response()->json(['message' => 'Query not found'], 404);
        }

        //Haal alle criterium_group records op bij deze query
        $groups = DB::table('criterium_group')
            ->where('query', $queryRow->id)
            ->get();

        //Bouw de Query Builder
        $stationsQuery = DB::table('station')
            ->join('nearestlocation', 'nearestlocation.station_name', '=', 'station.name');

        //Voeg de dynamische filtering toe
        if (!$groups->isEmpty()) {
            $stationsQuery->where(function($main) use ($groups) {
                foreach ($groups as $group) {
                    //Haal criteria op voor deze groep
                    $criteria = DB::table('criterium')
                        ->where('group', $group->id)
                        ->get();

                    //Als er geen criteria in deze groep zijn, sla deze groep over
                    if ($criteria->isEmpty()) {
                        continue;
                    }

                    if ($group->operator == 2) { // OR tussen groepen
                        $main->orWhere(function($subQuery) use ($criteria) {
                            $isFirstCriterium = true;
                            foreach ($criteria as $crit) {
                                $this->applyOneCriterium($subQuery, $crit, $isFirstCriterium);
                                $isFirstCriterium = false;
                            }
                        });
                    } else { // AND tussen groepen
                        $main->where(function($subQuery) use ($criteria) {
                            $isFirstCriterium = true;
                            foreach ($criteria as $crit) {
                                $this->applyOneCriterium($subQuery, $crit, $isFirstCriterium);
                                $isFirstCriterium = false;
                            }
                        });
                    }
                }
            });
        }

        //Selecteer de stations
        $stations = $stationsQuery
            ->select('station.*', 'nearestlocation.country_code', 'nearestlocation.administrative_region1')
            ->get();

        if ($stations->isEmpty()) {
            return response()->json([
                'contract_identifier' => $contract->identifier,
                'query_id'            => $queryRow->id,
                'stations'            => [],
                'message'             => 'No stations matched these criteria'
            ], 404);
        }

        return response()->json([
            'contract_identifier' => $contract->identifier,
            'query_id'            => $queryRow->id,
            'stations'            => $stations,
        ]);
    }
    public function getStationDetails($identifier, $name)
    {
        //Zoek contract
        $contract = DB::table('contract')
            ->where('identifier', $identifier)
            ->first();

        if (!$contract) {
            return response()->json(['message' => 'Contract not found'], 404);
        }

        //Zoek station op basis van naam
        $station = DB::table('station')
            ->where('name', $name)
            ->first();

        if (!$station) {
            return response()->json(['message' => 'Station not found'], 404);
        }

        //Nearest location ophalen
        $nearestLocation = DB::table('nearestlocation')
            ->where('station_name', $station->name)
            ->first();

        return response()->json([
            'contract_identifier' => $contract->identifier,
            'station'             => $station,
            'nearestLocation'     => $nearestLocation,
        ]);
    }

    private function applyOneCriterium($builder, $crit, &$isFirstCriterium)
    {
        //Bepaal of het AND of OR is
        $operatorValue = $crit->operator;
        if (!is_numeric($operatorValue)) {
            if (strtoupper($operatorValue) === 'AND') {
                $operatorValue = 1;
            } elseif (strtoupper($operatorValue) === 'OR') {
                $operatorValue = 2;
            }
        }
        $logicalOperator = ($operatorValue == 2) ? 'OR' : 'AND';

        //Haal de groep op om het type te bepalen
        $groupRow = DB::table('criterium_group')->where('id', $crit->group)->first();
        if (!$groupRow) {
            return;
        }

        $typeId = $groupRow->type;
        $typeRow = DB::table('criterium_type')->where('id', $typeId)->first();
        if (!$typeRow) {
            return;
        }

        //Bepaal de kolomnaam
        $columnName = $this->mapCriteriumTypeToColumn($typeRow);

        //Bepaal de vergelijkingsoperator
        $comparisonSymbol = $this->getComparisonSymbol($crit->comparison);

        //Bepaal de waarde
        $value = null;
        if ($crit->value_type == 1) {
            $value = $crit->int_value;
        } elseif ($crit->value_type == 2) {
            $value = $crit->string_value;
        } elseif ($crit->value_type == 3) {
            $value = $crit->float_value;
        }

        //Pas de WHERE toe
        //orWhere als de operator OR ,where als AND.
        if ($isFirstCriterium) {
            $builder->where($columnName, $comparisonSymbol, $value);
        } else {
            if ($logicalOperator === 'OR') {
                $builder->orWhere($columnName, $comparisonSymbol, $value);
            } else {
                $builder->where($columnName, $comparisonSymbol, $value);
            }
        }
    }

    private function mapCriteriumTypeToColumn($typeRow)
    {
        $tableName = strtolower($typeRow->referenced_table);
        $fieldName = strtolower($typeRow->referenced_field);
        return $tableName . '.' . $fieldName;
    }

    private function getComparisonSymbol($comparisonId)
    {
        switch ($comparisonId) {
            case 1:
                return '=';
            case 2:
                return '<';
            case 3:
                return '<=';
            case 4:
                return '>';
            case 5:
                return '>=';
            case 6:
                return '<>';
            default:
                return '=';
        }
    }
}
