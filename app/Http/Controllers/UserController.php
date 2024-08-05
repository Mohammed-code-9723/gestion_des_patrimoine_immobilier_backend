<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Events\UserAction;
use Illuminate\Support\Facades\Log;


class UserController extends Controller
{
    public function allUsers(){
        $allUsers = User::with('workspaces')->get();
        return response()->json(['users' => $allUsers]);
    } 

    public function login(Request $request){
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        event(new UserAction(Auth::id(), 'login', 'User logged in.'));
        return response()->json(['token' => $token, 'user' => Auth::user()]);
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            event(new UserAction(Auth::id(), 'logout', 'User logged out.'));
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
            'password' => 'required|string',
            'role' => 'required|string',
            'permissions' => 'required'
        ]);

        Log::info('Validated data:', $validatedData);

        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'password_confirmation' => $validatedData['password'],
            'role' => $validatedData['role'],
            'permissions' => json_encode($validatedData['permissions'])
        ]);

        event(new UserAction(Auth::id(), 'register', 'User registered.'));
        return response()->json(['message' => 'User added successfully']);
    }

    public function refresh(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $token = JWTAuth::refresh(JWTAuth::getToken());

            event(new UserAction($user->id, 'token_refresh', 'Token refreshed.'));
            return response()->json([
                'token' => $token,
                'user' => $user
            ]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token refresh failed'], 401);
        }
    }

    public function deleteUser($id){
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->delete();
        event(new UserAction(Auth::id(), 'delete_user', 'User deleted.'));
        return response()->json(['message' => 'User deleted successfully']);
    }

    public function update(Request $request)
    {
        
        Log::info('updated data:', $request->all());
        $validatedData = $request->validate([
            'id'=>'required|integer',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string',
            'role' => 'required|string|in:admin,manager,ingenieur,technicien',
        ]);
        

        // $userData=$request->input('user');
        
        $user = User::find($validatedData['id']);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }


        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        
        if ($request->filled('password')) {
            $user->password = bcrypt($validatedData['password']);
        }
        
        $user->password_confirmation =$validatedData['password'];
        $user->role =$validatedData['role'];

        $user->save();

        event(new UserAction(Auth::id(), 'update_user', 'User updated.'));
        return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
    }

    public function updateUsersPermissions(Request $request){
        $validator = Validator::make($request->all(), [
            'usersPermissions' => 'required|array',
            'usersPermissions.*.id' => 'required|exists:users,id',
            'usersPermissions.*.permissions' => 'required|json',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $usersData = $request->input('usersPermissions');

        foreach ($usersData as $userData) {
            $user = User::find($userData['id']);
            if ($user) {
                $user->permissions = $userData['permissions'];
                $user->save();
            }
        }

        event(new UserAction(Auth::id(), 'update_permissions', 'Permissions updated.'));
        return response()->json(['message' => 'Permissions updated successfully']);
    }
}
