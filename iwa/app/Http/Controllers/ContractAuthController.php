<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ContractAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('contract.login');
    }

    public function login(Request $request)
    {
        $data = $request->isJson() ? $request->json()->all() : $request->all();

        $request->replace($data);

        $request->validate([
            'useridentifier' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = DB::table('contract_users')
            ->where('useridentifier', $request->useridentifier)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => '❌ Error: wrong credentials!.'], 401);
        }

        $contract = DB::table('contract')->where('id', $user->contractID)->first();

        if (!$contract) {
            return response()->json(['message' => 'Contract not found.'], 404);
        }

        session([
            'contract_user_identifier' => $user->useridentifier,
            'contract_user_name' => $user->name,
            'contract_user_is_admin' => $user->is_admin,
            'contract_identifier' => $contract->identifier,
        ]);

        return response()->json([
            'message' => 'Logged in successfully.!',
            'user' => [
                'useridentifier' => $user->useridentifier,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'contractID' => $user->contractID,
                'is_admin' => $user->is_admin,
            ],
            'contract_identifier' => $contract->identifier,
            'token' => 'DUMMY_TOKEN_123'
        ]);
    }



    public function logout(Request $request)
    {
        session()->forget([
            'contract_user_identifier',
            'contract_user_name',
            'contract_user_is_admin',
            'contract_identifier',
        ]);

        return redirect('/contract/login');
    }
}
