<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = DB::table('osaka_users')->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Ongeldige inloggegevens'], 401);
        }

        return response()->json([
            'message' => 'Succesvol ingelogd',
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'is_admin' => $user->is_admin
            ]
        ]);
    }
}
