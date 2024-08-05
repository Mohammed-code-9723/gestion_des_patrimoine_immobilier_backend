<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Events\UserAction;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProjectController extends Controller
{
    public function allProjects()
    {
        $projects = Project::with('scenarios')->get();
        return response()->json(["projects"=>$projects]);
    }
    public function index($id)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $workspace = $user->workspaces()->find($id);

        if (!$workspace) {
            return response()->json(['message' => 'Workspace not found'], 404);
        }

        $projects = $workspace->projects()->get();

        // Dispatch event
        event(new UserAction($user->id, 'viewed_projects', 'User viewed projects in workspace ' . $id));

        return response()->json($projects);
    }

    public function store(Request $request, $id)
    {
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
            'description' => 'required|string|max:255|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $project = new Project();
        $project->name = $request->name;
        $project->description = $request->description;
        $project->workspace_id = $workspace->id;
        $project->save();

        // Dispatch event
        event(new UserAction($user->id, 'created_project', 'User created a new project in workspace ' . $id));

        return response()->json($project);
    }

    public function show($workspaceId, $projectId)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $workspace = $user->workspaces()->find($workspaceId);

        if (!$workspace) {
            return response()->json(['message' => 'Workspace not found'], 404);
        }

        $project = $workspace->projects()->find($projectId);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        // Dispatch event
        event(new UserAction($user->id, 'viewed_project', 'User viewed project ' . $projectId . ' in workspace ' . $workspaceId));

        return response()->json($project);
    }

    public function update(Request $request, $workspaceId, $projectId)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $workspace = $user->workspaces()->find($workspaceId);

        if (!$workspace) {
            return response()->json(['message' => 'Workspace not found'], 404);
        }

        $project = $workspace->projects()->find($projectId);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|min:3',
            'description' => 'required|string|max:255|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $project->name = $request->name;
        $project->description = $request->description;
        $project->save();

        // Dispatch event
        event(new UserAction($user->id, 'updated_project', 'User updated project ' . $projectId . ' in workspace ' . $workspaceId));

        return response()->json($project);
    }

    public function destroy($workspaceId, $projectId)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $workspace = $user->workspaces()->find($workspaceId);

        if (!$workspace) {
            return response()->json(['message' => 'Workspace not found'], 404);
        }

        $project = $workspace->projects()->find($projectId);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $project->delete();

        // Dispatch event
        event(new UserAction($user->id, 'deleted_project', 'User deleted project ' . $projectId . ' in workspace ' . $workspaceId));

        return response()->json(['message' => 'Project deleted']);
    }
}

