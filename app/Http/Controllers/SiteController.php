<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Events\UserAction;

class SiteController extends Controller
{
    public function allSites(){
        $allSites=Site::with('buildings')->get();
        return response()->json($allSites);
    }
    
    public function index($workspaceId)
    {
        // Retrieve the authenticated user
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Retrieve the workspace
        $workspace = $user->workspaces()->find($workspaceId);

        if (!$workspace) {
            return response()->json(['message' => 'Workspace not found'], 404);
        }

        // Retrieve the projects
        $sites = $workspace->sites()->get();
        return response()->json($sites);
    }

    public function store(Request $request, $workspaceId)
    {
        // Retrieve the authenticated user
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Retrieve the workspace
        $workspace = $user->workspaces()->find($workspaceId);

        if (!$workspace) {
            return response()->json(['message' => 'Workspace not found'], 404);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'code'=> 'required|string|max:255|min:3',
            'name'=> 'required|string|max:255|min:3',
            'activity'=> 'required|string|max:255|min:3',
            'address'=> 'required|string|max:255|min:3',
            'zipcode'=> 'required|string|max:255|min:3',
            'city'=> 'required|string|max:255|min:3',
            'country'=> 'required|string|max:255|min:3',
            'floor_area'=> 'required|string|max:255|min:3',
        ]);

        // Handle validation failures
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $site = new Site();
        $site->code = $request->code;
        $site->name = $request->name;
        $site->activity = $request->activity;
        $site->address = $request->address;
        $site->zipcode = $request->zipcode;
        $site->city = $request->city;
        $site->country = $request->country;
        $site->floor_area = $request->floor_area;
        $site->workspace_id = $workspace->id;
        $site->save();

        event(new UserAction($user->id, 'created_site', 'User created a new site'));

        return response()->json($site);
    }

    public function show($workspaceId, $siteId)
    {
        // Retrieve the authenticated user
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Retrieve the workspace
        $workspace = $user->workspaces()->find($workspaceId);

        if (!$workspace) {
            return response()->json(['message' => 'Workspace not found'], 404);
        }

        // Retrieve the project
        $site = $workspace->sites()->find($siteId);

        if (!$site) {
            return response()->json(['message' => 'site not found'], 404);
        }

        return response()->json($site);
    }

    public function update(Request $request, $workspaceId, $siteId)
    {
        // Retrieve the authenticated user
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Retrieve the workspace
        $workspace = $user->workspaces()->find($workspaceId);

        if (!$workspace) {
            return response()->json(['message' => 'Workspace not found'], 404);
        }

        // Retrieve the project
        $site = $workspace->sites()->find($siteId);

        if (!$site) {
            return response()->json(['message' => 'site not found'], 404);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'code'=> 'required|string|max:255|min:3',
            'name'=> 'required|string|max:255|min:3',
            'activity'=> 'required|string|max:255|min:3',
            'address'=> 'required|string|max:255|min:3',
            'zipcode'=> 'required|string|max:255|min:3',
            'city'=> 'required|string|max:255|min:3',
            'country'=> 'required|string|max:255|min:3',
            'floor_area'=> 'required|string|max:255|min:3',
        ]);

        // Handle validation failures
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $site->code = $request->code;
        $site->name = $request->name;
        $site->activity = $request->activity;
        $site->address = $request->address;
        $site->zipcode = $request->zipcode;
        $site->city = $request->city;
        $site->country = $request->country;
        $site->floor_area = $request->floor_area;
        $site->workspace_id = $workspace->id;
        $site->save();

        event(new UserAction($user->id, 'updated_site', 'User updated a site'));

        return response()->json($site);
    }

    public function destroy($workspaceId, $siteId)
    {
        // Retrieve the authenticated user
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Retrieve the workspace
        $workspace = $user->workspaces()->find($workspaceId);

        if (!$workspace) {
            return response()->json(['message' => 'Workspace not found'], 404);
        }

        // Retrieve the project
        $site = $workspace->sites()->find($siteId);

        if (!$site) {
            return response()->json(['message' => 'site not found'], 404);
        }

        $site->delete();

        event(new UserAction($user->id, 'deleted_site', 'User deleted a site'));

        return response()->json(['message' => 'site deleted']);
    }
}
