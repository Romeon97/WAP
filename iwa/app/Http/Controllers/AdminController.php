<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(\App\Http\Middleware\AdminMiddleware::class);
    }

    public function manageUsers()
    {
        $users = User::all(); // Haal alle gebruikers op
        $roles = DB::table('userroles')->get(); // Haal alle rollen op

        return view('admin.manage-users', compact('users', 'roles'));
    }


    public function updateUsers(Request $request)
    {
        $users = $request->input('users');

        foreach ($users as $id => $userData) {
            $user = User::findOrFail($id);
            $user->update([
                'first_name' => $userData['first_name'],
                'name' => $userData['name'],
                'email' => $userData['email'],
                'employee_code' => $userData['employee_code'],
                'user_role' => $userData['user_role'],
            ]);
        }

        return redirect()->route('admin.manageUsers')->with('success', 'Gebruikers succesvol bijgewerkt!');
    }

    public function deleteUser($id)
    {
        $user = \App\Models\User::findOrFail($id);

        if (auth()->id() === $user->id) {
            return back()->with('error', 'Je kunt jezelf niet verwijderen.');
        }

        $user->delete();

        return back()->with('success', 'Gebruiker succesvol verwijderd.');
    }
}
