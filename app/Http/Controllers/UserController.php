<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string'
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'email' =>  $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role']
        ]);

        $token = $user->createToken($user['email'])->plainTextToken;

        if ($user) {
            return response()->json([
                'message' => 'Successfully created user',
                'token' => $token,
                'user' => $user
            ], 201);
        } else {
            return response()->json([
                'message' => 'Something went wrong'
            ], response()->status());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * NON standard added by developer
     */
    public function login(Request $request) : JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required | email',
            'password' => 'required'
        ]);
        
        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

         $token = $user->createToken($user->email)->plainTextToken;

         return response()->json([
            'message' => 'Loggedin',
            'token' => $token
         ], 200);
    }
}
