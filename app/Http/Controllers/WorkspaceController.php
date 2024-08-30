<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Events\UserAction;
use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    public function allWorkspaces(){
        $allWorkspaces = Workspace::with('projects')->with('sites')->get();
        return response()->json($allWorkspaces);
    }

    //update:
    public function updateWorkspace(Request $request,$id){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|min:3',
            'description' => 'required|string|max:255|min:3'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $workspace = Workspace::find($id);
        if (!$workspace) {
            return response()->json(['error' => 'Workspace not found'], 404);
        }

        $workspace->name=$request->name;
        $workspace->description=$request->description;
        $workspace->save();

        event(new UserAction(Auth::id(), 'update_workspace', 'Workspace deleted.'));
        return response()->json(['message' => 'Workspace updated successfully']);
    }

    //delete:
    public function deleteWorkspace($id){
        $workspace = Workspace::find($id);
        if (!$workspace) {
            return response()->json(['error' => 'Workspace not found'], 404);
        }

        $workspace->delete();
        event(new UserAction(Auth::id(), 'delete_workspace', 'Workspace deleted.'));
        return response()->json(['message' => 'Workspace deleted successfully']);
    }

    public function addWorkspace(Request $request,$userId)
    {
        try {
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
                'user_id' => $userId
            ]);

            if ($workspace) {
                event(new UserAction($userId, 'create_workspace', 'Created a new workspace.'));
                return response()->json(['message' => 'Workspace created successfully.'], 201);
            } else {
                return response()->json(['message' => 'Workspace not created'], 500);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'Could not authenticate token'], 401);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }

            $workspaces = $user->workspaces()->with('projects')->with('sites')->get();
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
                event(new UserAction($user->id, 'create_workspace', 'Created a new workspace.'));
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

            event(new UserAction($user->id, 'update_workspace', 'Updated a workspace.'));
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

            event(new UserAction($user->id, 'delete_workspace', 'Deleted a workspace.'));
            return response()->json(['message' => 'Workspace deleted successfully'], 200);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Could not authenticate token'], 401);
        }
    }
}
