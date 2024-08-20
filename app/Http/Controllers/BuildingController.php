<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;
use App\Events\UserAction;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BuildingController extends Controller
{
    public function index()
    {
        $allBuildings=Building::with('components')->get();
        return response()->json(["buildings"=>$allBuildings]);
    }

    public function store(Request $request)
    {
        // Log::info($request->all());
        $validatedData = $request->validate([
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'activity' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'location' => 'required|json',
            'year_of_construction' => 'required|integer',
            'surface' => 'required|numeric',
            'type' => 'required|string|max:255',
            'level_count' => 'required|string',
            'site_id' => 'required|exists:sites,id',
        ]);

        $newBuilding=new Building();
        $newBuilding->code=$validatedData["code"];
        $newBuilding->name=$validatedData["name"];
        $newBuilding->activity=$validatedData["activity"];
        $newBuilding->address=$validatedData["address"];
        $newBuilding->location=$validatedData["location"];
        $newBuilding->year_of_construction=(int)($validatedData["year_of_construction"]);
        $newBuilding->surface=(double)($validatedData["surface"]);
        $newBuilding->type=$validatedData["type"];
        $newBuilding->level_count=$validatedData["level_count"];
        $newBuilding->site_id=$validatedData["site_id"];
        $newBuilding->save();

        Log::info("new building: ");
        Log::info($newBuilding);

        // Building::create($validatedData);

        // Retrieve the authenticated user
        $user = JWTAuth::parseToken()->authenticate();

        if ($user) {
            event(new UserAction($user->id, 'created_building', 'User created a new building'));
        }

        return response()->json(["message"=>"New building created successfully."]);
    }

    public function show(Building $building)
    {
        return $building;
    }

    public function update(Request $request,$building_id)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $validatedData = $request->validate([
            'code' => 'string|max:255',
            'name' => 'string|max:255',
            'activity' => 'string|max:255',
            'address' => 'string|max:255',
            'location' => 'string|max:255',
            'year_of_construction' => 'integer',
            'surface' => 'numeric',
            'type' => 'string|max:255',
            'level_count' => 'string',
            'site_id' => 'exists:sites,id',
        ]);

        $building=Building::find($building_id);
        $building->update($validatedData);

        if ($user) {
            event(new UserAction($user->id, 'updated_building', 'User updated a building'));
        }

        return response()->json(["message"=>"Building updated successfully."]);
    }

    public function destroy(Building $building)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $building->delete();

        event(new UserAction($user->id, 'deleted_building', 'User deleted a building'));

        return response()->json(['message'=>'Building deleted successfully.']);
    }

    public function destroyBuilding(Request $request)
    {
        // $user = JWTAuth::parseToken()->authenticate();
        $building_id=$request->id;
        // if (!$user) {
        //     return response()->json(['message' => 'Unauthenticated'], 401);
        // }
        Log::info("building: ");
        Log::info($building_id);
        $building=Building::find($building_id);
        $building->delete();

        event(new UserAction(Auth::id(), 'deleted_building', 'User deleted a building'));

        return response()->json(['message'=>'Building deleted successfully.']);
    }
}
