<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StationFilterController extends Controller
{
    public function getStationsForQuery($contractId, $queryId)
    {
        $contract = DB::table('contract')->where('id', $contractId)->first();
        if (!$contract) {
            return response()->json(['message' => 'Contract not found'], 404);
        }

        $queryRow = DB::table('query')
            ->where('id', $queryId)
            ->where('contract_id', $contractId)
            ->first();
        if (!$queryRow) {
            return response()->json(['message' => 'Query not found for this contract'], 404);
        }

        $groups = DB::table('criterium_group')
            ->where('query', $queryId)
            ->get();


        $stationsQuery = DB::table('station')
            ->join('nearestlocation', 'nearestlocation.station_name', '=', 'station.name');

        $stationsQuery->where(function($main) use ($groups) {
            foreach ($groups as $index => $groupRow) {

                //De operator tussen groups
                $groupOperator = $groupRow->operator; // 1=And, 2=Or

                $main->where(function($groupSub) use ($groupRow) {
                    $criteria = DB::table('criterium')->where('group', $groupRow->id)->get();

                    $firstCriterium = true;
                    foreach ($criteria as $crit) {
                        $this->applyOneCriterium($groupSub, $crit, $firstCriterium);
                        $firstCriterium = false;
                    }
                }, ($groupOperator === 2) ? 'OR' : 'AND');
            }
        });

        //Haal de uiteindelijke resultaten op
        $result = $stationsQuery->select('station.*', 'nearestlocation.country_code', 'nearestlocation.administrative_region1')->get();

        if ($result->isEmpty()) {
            return response()->json([
                'message' => 'No stations matched the criteria for this query'
            ], 404);
        }

        return response()->json([
            'contract_id' => $contractId,
            'query_id'    => $queryId,
            'stations'    => $result
        ]);
    }


    /**
     * Hulpmethode:
     * - "Plakt" één criterium (bv. landcode=NO, elevation>=200) aan je Query Builder-subclosure
     *   via where() of orWhere().
     * - We bepalen op basis van:
     *   - operator (1=And,2=Or)
     *   - comparison_operator_type (1='=',5='>=', etc.)
     *   - criterium_type (bv. landcode= nearestlocation.country_code)
     *   of we ->where('nearestlocation.country_code', '=', 'NO') etc.
     */
    private function applyOneCriterium($queryBuilder, $crit, &$isFirst)
    {
        $logicalOperator = 'AND'; // default
        if ($crit->operator == 2) {
            $logicalOperator = 'OR';
        }

        $cTypeRow = DB::table('criterium_group')
            ->where('id', $crit->group)
            ->first();

        if ($cTypeRow) {
            $typeId = $cTypeRow->type;
            $typeRow = DB::table('criterium_type')->where('id', $typeId)->first();
            if ($typeRow) {
                $column = $this->mapCriteriumTypeToColumn($typeRow);
                $comparisonSymbol = $this->getComparisonSymbol($crit->comparison);

                $value = null;
                if ($crit->value_type == 1) {
                    // int
                    $value = $crit->int_value;
                } elseif ($crit->value_type == 2) {
                    // string
                    $value = $crit->string_value;
                } elseif ($crit->value_type == 3) {
                    // float
                    $value = $crit->float_value;
                }

                if ($isFirst) {
                    $queryBuilder->where($column, $comparisonSymbol, $value);
                } else {
                    if ($logicalOperator === 'OR') {
                        $queryBuilder->orWhere($column, $comparisonSymbol, $value);
                    } else {
                        $queryBuilder->where($column, $comparisonSymbol, $value);
                    }
                }
            }
        }
    }

    /**
     * Hulpmethode:
     *   Geeft een kolomnaam terug op basis van 'referenced_table' en 'referenced_field' uit criterium_type,
     *   bijvoorbeeld:
     *   'Station' + 'Elevation' => 'station.elevation'
     *   'Nearestlocation' + 'Administrative_region1' => 'nearestlocation.administrative_region1'
     */
    private function mapCriteriumTypeToColumn($typeRow)
    {
        $table = strtolower($typeRow->referenced_table);    // bijv. 'Station' -> 'station'
        $field = strtolower($typeRow->referenced_field);    // bijv. 'Elevation' -> 'elevation'
        return $table . '.' . $field;
    }

    /**
     * Hulpmethode: "Vergelijking" (comparison_operator_type)
     */
    private function getComparisonSymbol($comparisonId)
    {
        switch($comparisonId) {
            case 1: return '=';  // Equal
            case 2: return '<';
            case 3: return '<=';
            case 4: return '>';
            case 5: return '>=';
            case 6: return '<>'; // Of '!=' (Not)
        }
        // default
        return '=';
    }
}
