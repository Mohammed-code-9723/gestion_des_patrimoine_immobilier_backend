<?php

namespace App\Http\Controllers;

use App\Models\Scenario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Events\UserAction;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class ScenarioController extends Controller
{

    

    // public function index($projectId)
    // {
    //     $user = JWTAuth::parseToken()->authenticate();

    //     if (!$user) {
    //         return response()->json(['message' => 'Unauthenticated'], 401);
    //     }

    //     $scenarios = Scenario::where('project_id', $projectId)->get();

    //     // Dispatch event
    //     event(new UserAction($user->id, 'viewed_scenarios', 'User viewed scenarios for project ' . $projectId));

    //     return response()->json($scenarios);
    // }

    public function store(Request $request)
    {
        Log::info("req: ");
        Log::info($request->all());
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|min:3',
            'start_year' => 'required|integer|min:1800',
            'end_year' => 'required|integer|min:1800',
            // 'duration' => 'required|string|max:255',
            'maintenance_strategy' => 'required|string|max:255',
            'budgetary_constraint' => 'required|string|max:255',
            'status' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $scenario = new Scenario();
        $scenario->name = $request->name;
        $scenario->start_year = $request->start_year;
        $scenario->end_year = $request->end_year;
        // $scenario->duration = $request->duration;
        $scenario->maintenance_strategy = $request->maintenance_strategy;
        $scenario->budgetary_constraint = $request->budgetary_constraint;
        $scenario->status = $request->status;
        $scenario->project_id = $request->project_id;
        $scenario->save();

        Log::info("scenarios : ");
        Log::info($scenario);

        // Dispatch event
        event(new UserAction($user->id, 'created_scenario', 'User created a new scenario for project ' ));

        return response()->json(["message"=>"scenario created successfully."]);
    }

    public function show($projectId, $scenarioId)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $scenario = Scenario::where('project_id', $projectId)->find($scenarioId);

        if (!$scenario) {
            return response()->json(['message' => 'Scenario not found'], 404);
        }

        // Dispatch event
        event(new UserAction($user->id, 'viewed_scenario', 'User viewed scenario ' . $scenarioId . ' for project ' . $projectId));

        return response()->json($scenario);
    }

    public function update(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $scenario = Scenario::find($request->id);

        if (!$scenario) {
            return response()->json(['message' => 'Scenario not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|min:3',
            'start_year' => 'required|integer|min:1800',
            'end_year' => 'required|integer|min:1800',
            // 'duration' => 'required|string|max:255',
            'maintenance_strategy' => 'required|string|max:255',
            'budgetary_constraint' => 'required|string|max:255',
            'status' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $scenario->name = $request->name;
        $scenario->start_year = $request->start_year;
        $scenario->end_year = $request->end_year;
        // $scenario->duration = $request->duration;
        $scenario->maintenance_strategy = $request->maintenance_strategy;
        $scenario->budgetary_constraint = $request->budgetary_constraint;
        $scenario->status = $request->status;
        $scenario->save();

        // Dispatch event
        event(new UserAction($user->id, 'updated_scenario', 'User updated scenario '));

        return response()->json(["message"=>"The scenario updated successfully."]);
    }

    public function destroy(Request $request)
    {
        Log::info('request:');
        Log::info($request->all());
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $scenario = Scenario::find($request->id);

        if (!$scenario) {
            return response()->json(['message' => 'Scenario not found'], 404);
        }

        $scenario->delete();

        event(new UserAction($user->id, 'deleted_scenario', 'User deleted scenario '));

        return response()->json(['message' => 'Scenario deleted successfully.']);
    }
}
