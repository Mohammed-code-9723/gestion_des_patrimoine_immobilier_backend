<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Scenario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ScenarioController extends Controller
{
    public function index($projectId)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $scenarios = Scenario::where('project_id', $projectId)->get();
        return response()->json($scenarios);
    }

    public function store(Request $request, $projectId)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|min:3',
            'start_year' => 'required|integer|min:1900|max:2100',
            'end_year' => 'required|integer|min:1900|max:2100',
            'duration' => 'required|string|max:255',
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
        $scenario->duration = $request->duration;
        $scenario->maintenance_strategy = $request->maintenance_strategy;
        $scenario->budgetary_constraint = $request->budgetary_constraint;
        $scenario->status = $request->status;
        $scenario->project_id = $projectId;
        $scenario->save();

        return response()->json($scenario);
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

        return response()->json($scenario);
    }

    public function update(Request $request, $projectId, $scenarioId)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $scenario = Scenario::where('project_id', $projectId)->find($scenarioId);

        if (!$scenario) {
            return response()->json(['message' => 'Scenario not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|min:3',
            'start_year' => 'required|integer|min:1900|max:2100',
            'end_year' => 'required|integer|min:1900|max:2100',
            'duration' => 'required|string|max:255',
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
        $scenario->duration = $request->duration;
        $scenario->maintenance_strategy = $request->maintenance_strategy;
        $scenario->budgetary_constraint = $request->budgetary_constraint;
        $scenario->status = $request->status;
        $scenario->save();

        return response()->json($scenario);
    }

    public function destroy($projectId, $scenarioId)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $scenario = Scenario::where('project_id', $projectId)->find($scenarioId);

        if (!$scenario) {
            return response()->json(['message' => 'Scenario not found'], 404);
        }

        $scenario->delete();

        return response()->json(['message' => 'Scenario deleted']);
    }
}
