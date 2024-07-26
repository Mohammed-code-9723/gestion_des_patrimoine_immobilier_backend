<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function allUsers(){
        $allUsers=User::with('workspaces')->get();
        return response()->json(['users'=>$allUsers]);
    } 

    public function login(Request $request){
        $credentials=$request->validate([
            "email"=>"required|email",
            "password"=>"required"
        ]);

        if(Auth::attempt($credentials)){
            $user = Auth::user();
            $token=$user->createToken('authToken')->plainTextToken;
            return response()->json([
                'user' => $user,
                'token' => $token,
            ]);
        } else {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }
    }

}

