<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Events\UserAction;
use App\Models\Workspace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


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

    public function addNewSite(Request $request, $workspaceId)
    {
        
        // Retrieve the workspace
        $workspace = Workspace::find($workspaceId);

        if (!$workspace) {
            return response()->json(['message' => 'Workspace not found'], 404);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'Code'=> 'required|string|max:255',
            'Name'=> 'required|string|max:255',
            'Activity'=> 'required|string|max:255',
            'Address'=> 'required|string|max:255',
            'Zipcode'=> 'required|string|max:255',
            'City'=> 'required|string|max:255',
            'Country'=> 'required|string|max:255',
            'Floor_area'=> 'required|string|max:255',
        ]);

        // Handle validation failures
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $site = new Site();
        $site->code = $request->Code;
        $site->name = $request->Name;
        $site->activity = $request->Activity;
        $site->address = $request->Address;
        $site->zipcode = $request->Zipcode;
        $site->city = $request->City;
        $site->country = $request->Country;
        $site->floor_area = $request->Floor_area;
        $site->workspace_id = $workspace->id;
        $site->save();

        event(new UserAction(Auth::id(), 'created_site', 'User created a new site'));

        return response()->json(["message"=>"Site created successfully."]);
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

    public function updateExistingSite(Request $request)
{

    Log::info('updated site data:', $request->all());
    // Find the site within the workspace
    $site = Site::find($request->id);

    if (!$site) {
        return response()->json(['message' => 'Site not found'], 404);
    }

    // Validate the request
    $validator = Validator::make($request->all(), [
        'code' => 'required|string|max:255',
        'name' => 'required|string|max:255',
        'activity' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'zipcode' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'country' => 'required|string|max:255',
        'floor_area' => 'required|string|max:255',
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
    $site->workspace_id = $request->workspace_id;
    $site->save();

    // Trigger an event
    event(new UserAction(Auth::id(), 'updated_site', 'User updated a site'));

    // Return the updated site as a response
    return response()->json(['message'=>'Site updated successfully']);
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

    public function deleteSite($siteId)
    {
        $site = Site::find($siteId);

        if (!$site) {
            return response()->json(['message' => 'site not found'], 404);
        }

        $site->delete();

        event(new UserAction(Auth::id(), 'deleted_site', 'User deleted a site'));

        return response()->json(['message' => 'site deleted successfully']);
    }
}
