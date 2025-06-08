<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function processLogin(Request $request)
    {
        $credentials = [
            'employee_code' => $request->input('username'),
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Haal de user_role naam op via de relatie
            $roleName = DB::table('userroles')
                ->where('id', $user->user_role)
                ->value('role'); // Haal alleen de rolnaam op

            // Sla de gebruikersnaam en rolnaam op in de sessie
            session([
                'user' => $user->name,
                'user_role' => $roleName
            ]);

            return redirect('/');
        } else {
            return redirect()->route('login')->with('error', '❌ Error: wrong credentials!');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // ✅ Toont het registratieformulier
    public function showRegister()
    {
        $roles = DB::table('userroles')->get();
        return view('register', compact('roles'));
    }


    public function processRegister(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'prefix' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'initials' => 'nullable|string|max:10',
            'email' => 'required|email|unique:users,email',
            'employee_code' => 'required|string|unique:users,employee_code',
            'password' => 'required|min:6|confirmed',
            'user_role' => 'required|exists:userroles,id',
        ]);


        // Rolbeperking op basis van ingelogde gebruiker
        $authUser = auth()->user();
        if ($authUser->user_role == 4 && in_array($request->user_role, [4, 6])) {
            return back()->with('error', 'Je mag deze rol niet toewijzen.');
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'prefix' => $request->prefix,
            'name' => $request->name,
            'initials' => $request->initials,
            'email' => $request->email,
            'employee_code' => $request->employee_code,
            'password' => Hash::make($request->password),
            'user_role' => $request->user_role,
        ]);

        return redirect()->route('admin.manageUsers')->with('success', '✅ Gebruiker aangemaakt.');

    }










}
