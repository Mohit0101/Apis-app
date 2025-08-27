<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request) {

        $validated = $request->validate([
        'name' => 'required|string|max:26',
        'email' => 'required|string|email|unique:users',
        'password' => 'required|string|min:7|max:15',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'sucess' => true,
            'message' => 'user created successfully'
        ], 201);
    }
}