<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;
use App\Events\UserAction;
use Tymon\JWTAuth\Facades\JWTAuth;


class BuildingController extends Controller
{
    public function index()
    {
        $allBuildings=Building::with('components')->get();
        return response()->json(["buildings"=>$allBuildings]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'activity' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'year_of_construction' => 'required|integer',
            'surface' => 'required|numeric',
            'type' => 'required|string|max:255',
            'level_count' => 'required|integer',
            'site_id' => 'required|exists:sites,id',
        ]);

        $building = Building::create($validatedData);

        // Retrieve the authenticated user
        $user = JWTAuth::parseToken()->authenticate();

        if ($user) {
            event(new UserAction($user->id, 'created_building', 'User created a new building'));
        }

        return $building;
    }

    public function show(Building $building)
    {
        return $building;
    }

    public function update(Request $request, Building $building)
    {
        $validatedData = $request->validate([
            'code' => 'string|max:255',
            'name' => 'string|max:255',
            'activity' => 'string|max:255',
            'address' => 'string|max:255',
            'year_of_construction' => 'integer',
            'surface' => 'numeric',
            'type' => 'string|max:255',
            'level_count' => 'integer',
            'site_id' => 'exists:sites,id',
        ]);

        $building->update($validatedData);

        $user = JWTAuth::parseToken()->authenticate();

        if ($user) {
            event(new UserAction($user->id, 'updated_building', 'User updated a building'));
        }

        return $building;
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
}
