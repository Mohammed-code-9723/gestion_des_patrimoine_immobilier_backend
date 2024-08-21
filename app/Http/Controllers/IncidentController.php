<?php
namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Events\UserAction;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class IncidentController extends Controller
{

    public function allIncidents(){
        $incidents = Incident::all();
        return response()->json(['allIncidents'=>$incidents]);

    }
    public function index($id)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $incidents =$user->incidents->where('building_id',$id)
                    ->orWhere('component_id',$id)->get();

        event(new UserAction($user->id, 'viewed_incidents', 'User viewed incidents '));

        return response()->json(['allIncidents'=>$incidents]);
    }

    public function store(Request $request)
    {

        Log::info("new incident data:");
        Log::info($request->all());

        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|min:3',
            'description' => 'required|string|max:255|min:3',
            'status' => 'required|string|max:50',
            'user_id' => 'required|exists:users,id',
            'building_id' => 'nullable|exists:buildings,id',
            'component_id' => 'nullable|exists:components,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        

        $incident = new Incident();
        $incident->title = $request->title;
        $incident->description = $request->description;
        $incident->status = $request->status;
        $incident->user_id = $request->user_id;
        $incident->building_id = $request->building_id;
        $incident->component_id = $request->component_id;
        $incident->save();

        // Dispatch event
        event(new UserAction($user->id, 'created_incident', 'User created an incident.'));

        return response()->json(['message'=>"New incident  created successfully ."]);
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

    public function update(Request $request, $incidentId)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }


        $incident = Incident::find($incidentId);

        if (!$incident) {
            return response()->json(['message' => 'Incident not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|min:3',
            'description' => 'required|string|max:255|min:3',
            'status' => 'required|string|max:50',
            'component_id' => 'required|exists:components,id',
            //remember to remove the building_id
            'building_id' => 'required|exists:buildings,id',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $incident->title = $request->title;
        $incident->description = $request->description;
        $incident->status = $request->status;
        $incident->component_id = $request->component_id;
        $incident->building_id = $request->building_id;
        $incident->user_id = $request->user_id;
        $incident->save();

        event(new UserAction($user->id, 'updated_incident', `User updated incident {$request->title} successfully.` ));

        return response()->json(['message'=> `Incident {$request->title} updated successfully.`]);
    }

    public function destroyIncident(Request $request)
    {
        Log::info('incident delete id:');
        Log::info($request->id);

        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $incident = Incident::find($request->id);

        if (!$incident) {
            return response()->json(['message' => 'Incident not found'], 404);
        }

        $incident->delete();

        event(new UserAction($user->id, 'deleted_incident', `User deleted incident {$incident->title} successfully.`));

        return response()->json(['message' => `User deleted incident {$incident->title} successfully.`]);
    }
}
