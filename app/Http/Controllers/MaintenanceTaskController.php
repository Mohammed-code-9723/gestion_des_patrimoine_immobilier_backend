<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Events\UserAction;
use App\Models\Workspace;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class MaintenanceTaskController extends Controller
{

    public function index(){
        $allMaintenancesBack=MaintenanceTask::all();
        return response()->json(["allMaintenancesBack"=>$allMaintenancesBack]);
    }

    public function allForT(){
        $user = JWTAuth::parseToken()->authenticate();
        // Log::info($request->all());

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        if($user->role==="technicien"){
            $allMaintenancesBack=MaintenanceTask::where('assigned_to',$user->id)->get();
            return response()->json(["allMaintenancesBack"=>$allMaintenancesBack]);
        }
    }

    public function store(Request $request){
        Log::info($request->all());

        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $validatedData = Validator::make($request->all(),[
            'task_name' => 'string|max:255',
            'description' => 'string',
            'priority' => 'string|in:Low,Medium,High',
            'status' => 'string|in:Pending,In Progress,Completed',
            'scheduled_date' => 'string',
            'completion_date' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'assigned_to' => 'required|exists:users,id',
            'building_id' => 'required|exists:buildings,id',
            'component_id' => 'nullable|exists:components,id',
        ]);

        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 400);
        }

        $newMaintenance=new MaintenanceTask();
        $newMaintenance->task_name = $request->task_name;
        $newMaintenance->description = $request->description;
        $newMaintenance->priority = $request->priority;
        $newMaintenance->status = $request->status;
        $newMaintenance->scheduled_date = $request->scheduled_date;
        $newMaintenance->completion_date = $request->completion_date;
        $newMaintenance->user_id = $request->user_id;
        $newMaintenance->assigned_to = $request->assigned_to;
        $newMaintenance->building_id = $request->building_id;
        $newMaintenance->component_id = $request->component_id;
        $newMaintenance->save();

        event(new UserAction($user->id, 'create maintenance', 'User created maintenance '));

        return response()->json(["message"=>"The maintenance is created successfully."]);
    }

    public function updateMaintenance(){
        
    }

    public function destroyMaintenance(){
        
    }
}