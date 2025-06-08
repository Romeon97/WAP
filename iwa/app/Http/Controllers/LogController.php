<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function index(Request $request)
    {
        //Alleen toegankelijk voor user_role 5 en 6
        if (!in_array(Auth::user()->user_role, [5, 6])) {
            abort(403, 'Unauthorized action.');
        }

        //Haal het filter op uit de query
        $selectedEndpoint = $request->input('endpoint');

        //Als een endpoint geselecteerd is, filter op die waarde
        if ($selectedEndpoint) {
            $logs = DB::table('endpoint_activity')
                ->where('endpoint_used', $selectedEndpoint)
                ->get();
        } else {
            $logs = DB::table('endpoint_activity')->get();
        }

        //Haal unieke endpoints op voor het filter-formulier
        $endpoints = DB::table('endpoint_activity')
            ->select('endpoint_used')
            ->distinct()
            ->get();

        return view('LogView', compact('logs', 'endpoints', 'selectedEndpoint'));
    }
}
