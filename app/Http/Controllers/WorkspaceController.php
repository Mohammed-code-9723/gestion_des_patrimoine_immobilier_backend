<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class WorkspaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }

            $workspaces = $user->workspaces;

            return response()->json($workspaces);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Could not authenticate token'], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|min:3',
                'description' => 'required|string|max:255|min:3',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $workspace = Workspace::create([
                'name' => $request->name,
                'description' => $request->description,
                'user_id' => $user->id
            ]);

            if ($workspace) {
                return response()->json($workspace, 201);
            } else {
                return response()->json(['message' => 'Workspace not created'], 500);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'Could not authenticate token'], 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }

            $workspace = $user->workspaces()->find($id);

            if (!$workspace) {
                return response()->json(['message' => 'Workspace not found'], 404);
            }

            return response()->json($workspace);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Could not authenticate token'], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }

            $workspace = $user->workspaces()->find($id);

            if (!$workspace) {
                return response()->json(['message' => 'Workspace not found'], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|min:3',
                'description' => 'required|string|max:255|min:3'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $workspace->name = $request->name;
            $workspace->description = $request->description;

            $workspace->save();

            return response()->json($workspace);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Could not authenticate token'], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }

            $workspace = $user->workspaces()->find($id);

            if (!$workspace) {
                return response()->json(['message' => 'Workspace not found'], 404);
            }

            $workspace->delete();

            return response()->json(['message' => 'Workspace deleted successfully'], 200);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Could not authenticate token'], 401);
        }
    }
}
