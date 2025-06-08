<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

class ContractUserController extends Controller
{
    public function beheer()
    {
        if (!session()->has('contract_user_identifier') || session('contract_user_is_admin') != 1) {
            return redirect('/contract/login')->withErrors(['Geen toegang']);
        }

        $users = DB::table('contract_users')
            ->select('id', 'useridentifier', 'name', 'email', 'role', 'is_admin')
            ->get();

        return view('contract.users', ['users' => $users]);
    }

    public function dashboard()
    {
        $contractIdentifier = session('contract_identifier');
        $userIdentifier = session('contract_user_identifier');

        $contract = DB::table('contract')->where('identifier', $contractIdentifier)->first();
        $user = DB::table('contract_users')->where('useridentifier', $userIdentifier)->first();

        if (!$contract || !$user) {
            return redirect('/contract/login')->withErrors(['User or contract not found']);
        }

        $userCount = DB::table('contract_users')
            ->where('contractID', $contract->id)
            ->count();

        $adminCount = DB::table('contract_users')
            ->where('contractID', $contract->id)
            ->where('is_admin', true)
            ->count();

        $subscription = DB::table('subscriptions')
            ->where('identifier', $contractIdentifier)
            ->first();

        return view('contract.dashboard', [
            'userCount' => $userCount,
            'adminCount' => $adminCount,
            'email' => $user->email,
            'subscriptionToken' => $subscription->token ?? 'Geen token gevonden',
        ]);
    }



    public function list($identifier)
    {
        $contract = DB::table('contract')->where('identifier', $identifier)->first();
        if (!$contract) return response()->json(['message' => 'Contract not found'], 404);

        $users = DB::table('contract_users')
            ->where('contractID', $contract->id)
            ->select('useridentifier', 'name', 'email', 'role', 'is_admin')
            ->get();

        return response()->json($users);
    }

    public function show($identifier, $userIdentifier)
    {
        $contract = DB::table('contract')->where('identifier', $identifier)->first();
        if (!$contract) return response()->json(['message' => 'Contract not found'], 404);

        $user = DB::table('contract_users')
            ->where('contractID', $contract->id)
            ->where('useridentifier', $userIdentifier)
            ->first();

        if (!$user) return response()->json(['message' => 'User not found'], 404);

        return response()->json($user);
    }

    public function store(Request $request, $identifier)
    {
        $adminUseridentifier = $request->header('X-User-Identifier');
        $adminUser = DB::table('contract_users')->where('useridentifier', $adminUseridentifier)->first();

        if (!$adminUser || !$adminUser->is_admin) {
            return response()->json(['message' => 'Not authorized'], 403);
        }

        $request->validate([
            'useridentifier' => 'required|unique:contract_users',
            'name' => 'required|string',
            'email' => 'required|email|unique:contract_users',
            'password' => 'required|string',
            'role' => 'required|string',
            'is_admin' => 'sometimes|boolean',
        ]);

        $contract = DB::table('contract')->where('identifier', $identifier)->first();
        if (!$contract) return response()->json(['message' => 'Contract not found'], 404);

        DB::table('contract_users')->insert([
            'useridentifier' => $request->useridentifier,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'contractID' => $contract->id,
            'is_admin' => $request->has('is_admin') && $request->is_admin ? 1 : 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'User created']);
    }

    public function storeFromWeb(Request $request)
    {
        $adminUseridentifier = session('contract_user_identifier');

        $adminUser = DB::table('contract_users')->where('useridentifier', $adminUseridentifier)->first();

        if (!$adminUser || !$adminUser->is_admin) {
            return redirect()->back()->withErrors(['Je hebt geen rechten om gebruikers aan te maken.']);
        }

        $request->validate([
            'useridentifier' => 'required|unique:contract_users',
            'name' => 'required|string',
            'email' => 'required|email|unique:contract_users',
            'password' => 'required|string|min:6',
            'role' => 'required|string',
            'is_admin' => 'sometimes|boolean',
        ]);

        DB::table('contract_users')->insert([
            'useridentifier' => $request->useridentifier,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'contractID' => $adminUser->contractID,
            'is_admin' => $request->has('is_admin') && $request->is_admin ? 1 : 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/contract/users')->with('success', 'Gebruiker succesvol toegevoegd.');
    }



    public function update(Request $request, $identifier, $userIdentifier)
    {
        $adminUser = DB::table('contract_users')
            ->where('useridentifier', $request->header('X-User-Identifier'))
            ->first();

        if (!$adminUser || !$adminUser->is_admin) {
            return response()->json(['message' => 'Not authorized'], 403);
        }

        $contract = DB::table('contract')->where('identifier', $identifier)->first();
        if (!$contract) return response()->json(['message' => 'Contract not found'], 404);

        $user = DB::table('contract_users')
            ->where('contractID', $contract->id)
            ->where('useridentifier', $userIdentifier)
            ->first();

        if (!$user) return response()->json(['message' => 'User not found'], 404);

        $data = $request->only(['name', 'email', 'role', 'is_admin']);
        $data['updated_at'] = now();

        DB::table('contract_users')->where('id', $user->id)->update($data);

        return response()->json(['message' => 'User updated']);
    }

    public function destroy($identifier, $userIdentifier)
    {
        $contract = DB::table('contract')->where('identifier', $identifier)->first();
        if (!$contract) return response()->json(['message' => 'Contract not found'], 404);

        $user = DB::table('contract_users')
            ->where('contractID', $contract->id)
            ->where('useridentifier', $userIdentifier)
            ->first();

        if (!$user) return response()->json(['message' => 'User not found'], 404);

        DB::table('contract_users')->where('id', $user->id)->delete();

        return response()->json(['message' => 'User permanently deleted']);
    }
}
