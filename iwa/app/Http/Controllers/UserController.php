<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = DB::table('osaka_users')->get();
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:osaka_users',
            'password' => 'required',
        ]);

        $id = DB::table('osaka_users')->insertGetId([
            'first_name' => $request->first_name,
            'infix' => $request->infix ?? null,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->is_admin ?? false,
            'contract_id' => 7,
        ]);

        return response()->json([
            'id' => $id,
            'first_name' => $request->first_name,
            'infix' => $request->infix ?? null,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'is_admin' => $request->is_admin ?? false,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
        ]);

        DB::table('osaka_users')->where('id', $id)->update([
            'first_name' => $request->first_name,
            'infix' => $request->infix ?? null,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'is_admin' => $request->is_admin ?? false,
        ]);

        return response()->json(['message' => 'User updated']);
    }

    public function destroy($id)
    {
        DB::table('osaka_users')->where('id', $id)->delete();
        return response()->json(['message' => 'User deleted']);
    }
}
