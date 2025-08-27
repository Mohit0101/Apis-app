<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function store(Request $request) {

        $validated = Validator::make($request->all(),
            [
                'name' => 'required|string|max:26',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:7|max:15',
            ]
        );

        if ($validated->fails()) {
            return response()->json([
                'status' => false,
                'error' =>  $validator->errors()
            ], 422);
        }

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

    // public function store(Request $request) {
    // $user = \App\Models\User::create([
    //     'name' => $request->name ?? 'Test User',
    //     'email' => $request->email ?? 'test' . rand(1,1000) . '@example.com',
    //     'password' => bcrypt($request->password ?? 'secret123'),
    // ]);

    // return response()->json($user, 201);
    // }
}