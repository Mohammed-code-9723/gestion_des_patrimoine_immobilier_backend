<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function allUsers(){
        $allUsers=User::with('workspaces')->get();
        return response()->json(['users'=>$allUsers]);
    } 

    public function login(Request $request){

        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return response()->json(['token' => $token, 'user' => Auth::user()]);
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Successfully logged out']);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to logout, please try again'], 500);
        }
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            "password_confirmation"=>$validatedData['password'],
            "role"=>$validatedData['role'],
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(['token' => $token, 'user' => $user], 201);
    }

    public function refresh(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $token = JWTAuth::refresh(JWTAuth::getToken());

            return response()->json([
                'token' => $token,
                'user' => $user
            ]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token refresh failed'], 401);
        }
    }

}

