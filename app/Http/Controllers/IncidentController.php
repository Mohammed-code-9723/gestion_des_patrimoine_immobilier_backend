<?php
namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Events\UserAction;
use Tymon\JWTAuth\Facades\JWTAuth;

class IncidentController extends Controller
{
    public function index($buildingId)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $incidents = Incident::where('building_id', $buildingId)->get();

        // Dispatch event
        event(new UserAction($user->id, 'viewed_incidents', 'User viewed incidents for building ' . $buildingId));

        return response()->json($incidents);
    }

    public function store(Request $request, $buildingId)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|min:3',
            'description' => 'required|string|max:255|min:3',
            'status' => 'required|string|max:50',
            'component_id' => 'required|exists:components,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $incident = new Incident();
        $incident->title = $request->title;
        $incident->description = $request->description;
        $incident->status = $request->status;
        $incident->user_id = $user->id;
        $incident->building_id = $buildingId;
        $incident->component_id = $request->component_id;
        $incident->save();

        // Dispatch event
        event(new UserAction($user->id, 'created_incident', 'User created an incident for building ' . $buildingId));

        return response()->json($incident);
    }

    public function show($buildingId, $incidentId)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $incident = Incident::where('building_id', $buildingId)->find($incidentId);

        if (!$incident) {
            return response()->json(['message' => 'Incident not found'], 404);
        }

        // Dispatch event
        event(new UserAction($user->id, 'viewed_incident', 'User viewed incident ' . $incidentId . ' for building ' . $buildingId));

        return response()->json($incident);
    }

    public function update(Request $request, $buildingId, $incidentId)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $incident = Incident::where('building_id', $buildingId)->find($incidentId);

        if (!$incident) {
            return response()->json(['message' => 'Incident not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|min:3',
            'description' => 'required|string|max:255|min:3',
            'status' => 'required|string|max:50',
            'component_id' => 'required|exists:components,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $incident->title = $request->title;
        $incident->description = $request->description;
        $incident->status = $request->status;
        $incident->component_id = $request->component_id;
        $incident->save();

        // Dispatch event
        event(new UserAction($user->id, 'updated_incident', 'User updated incident ' . $incidentId . ' for building ' . $buildingId));

        return response()->json($incident);
    }

    public function destroy($buildingId, $incidentId)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $incident = Incident::where('building_id', $buildingId)->find($incidentId);

        if (!$incident) {
            return response()->json(['message' => 'Incident not found'], 404);
        }

        $incident->delete();

        // Dispatch event
        event(new UserAction($user->id, 'deleted_incident', 'User deleted incident ' . $incidentId . ' for building ' . $buildingId));

        return response()->json(['message' => 'Incident deleted']);
    }
}
