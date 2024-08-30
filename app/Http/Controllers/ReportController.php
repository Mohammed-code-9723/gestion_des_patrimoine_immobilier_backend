<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\User;
use App\Models\Project;
use App\Models\Site;
use App\Models\Building;
use App\Models\MaintenanceTask;
use App\Events\UserAction;
use App\Models\Incident;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReportController extends Controller
{
    public function allReports(){
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        if($user->role==="superadmin"||$user->role==="admin"||$user->role==="manager"){
            $allReports=Report::all();
            $totalIncidents=Report::where('report_about','Building')->count();
            $resolvedIncidents=Incident::where('status','Closed')->count();
            $criticalIncidents=Incident::where('critical',true)->count();
            $allUserIncidents=Incident::all();
            $allUsersReports=User::all();
            $allBuildings=Building::all();
            $allProjects=Project::all();
            $allSites=Site::all();
            $allMaintenances=MaintenanceTask::all();

            // Log::info('data: ',["allReports"=>$allReports,'totalIncidents'=>$totalIncidents,'resolvedIncidents'=>$resolvedIncidents,'criticalIncidents'=>$criticalIncidents]);
            return response()->json([
                "allReports"=>$allReports,
                'totalIncidents'=>$totalIncidents,
                'resolvedIncidents'=>$resolvedIncidents,
                'criticalIncidents'=>$criticalIncidents,
                'allUserIncidents'=>$allUserIncidents,
                'allUsersReports'=>$allUsersReports,
                'allBuildings'=>$allBuildings,
                'allProjects'=>$allProjects,
                'allSites'=>$allSites,
                'allMaintenances'=>$allMaintenances,
            ]);

        }else if($user->role==="ingenieur"){
            $allReports=$user->reports;
            $totalIncidents=$user->reports->where('report_about','Incident')->count();
            $resolvedIncidents=$user->incidents->where('status','Closed')->count();
            $criticalIncidents=$user->incidents->where('critical',true)->count();
            $allUserIncidents=$user->incidents->get();
            $allUsersReports=User::where('responsible',$user->id)->get();
            $allBuildings=Building::all();
            $allProjects=Project::all();
            $allSites=Site::all();
            $allMaintenances=MaintenanceTask::where('user_id',$user->id)->get();
            // Log::info('data: ',["allReports"=>$allReports,'totalIncidents'=>$totalIncidents,'resolvedIncidents'=>$resolvedIncidents,'criticalIncidents'=>$criticalIncidents]);
            return response()->json([
                "allReports"=>$allReports,
                'totalIncidents'=>$totalIncidents,
                'resolvedIncidents'=>$resolvedIncidents,
                'criticalIncidents'=>$criticalIncidents,
                'allUserIncidents'=>$allUserIncidents,
                'allBuildings'=>$allBuildings,
                'allProjects'=>$allProjects,
                'allSites'=>$allSites,
                'allMaintenances'=>$allMaintenances,
            ]);
        }
    }

    public function addReport(Request $request){
        
        Log::info("request: ");
        Log::info($request->all());

        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|min:3',
            'report_about' => 'required|in:Incident,Maintenance,Building,Project,Site',
            'description' => 'required|string',
            'created_by' => 'required|exists:users,id',
            'project_id' => 'nullable|exists:projects,id',
            'site_id' => 'nullable|exists:sites,id',
            'building_id' => 'nullable|exists:buildings,id',
            'incident_id' => 'nullable|exists:incidents,id',
            'maintenance_id' => 'nullable|exists:maintenance_tasks,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $incident = new Report();
        $incident->title = $request->title;
        $incident->report_about = $request->report_about;
        $incident->description = $request->description;
        $incident->created_by = $request->created_by;
        $incident->site_id = $request->site_id;
        $incident->project_id = $request->project_id;
        $incident->building_id = $request->building_id;
        $incident->incident_id = $request->incident_id;
        $incident->maintenance_id = $request->maintenance_id;
        $incident->save();

        event(new UserAction($user->id, 'created report', 'User created a report.'));

        return response()->json(['message'=>"New report  created successfully ."]);
    }

    public function update(Request $request,$id){

        Log::info("id: ");
        Log::info($id);
        Log::info("request: ");
        Log::info($request->all());
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|min:3',
            'report_about' => 'required|in:Incident,Maintenance,Building,Project,Site',
            'description' => 'required|string',
            'created_by' => 'required|exists:users,id',
            'project_id' => 'nullable|exists:projects,id',
            'site_id' => 'nullable|exists:sites,id',
            'building_id' => 'nullable|exists:buildings,id',
            'incident_id' => 'nullable|exists:incidents,id',
            'maintenance_id' => 'nullable|exists:maintenance_tasks,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $findReport=Report::find($id);
        $findReport->title=$request->title;
        $findReport->report_about = $request->report_about;
        $findReport->description = $request->description;
        $findReport->created_by = $request->created_by;
        $findReport->site_id = $request->site_id;
        $findReport->project_id = $request->project_id;
        $findReport->building_id = $request->building_id;
        $findReport->incident_id = $request->incident_id;
        $findReport->maintenance_id = $request->maintenance_id;
        $findReport->save();

        event(new UserAction($user->id, 'updated report ', 'User updated a report.'));

        return response()->json(['message'=>"Report  updated successfully ."]);
    }
    public function destroyReport(Request $request){
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        

        if($request->id){
            Report::find($request->id)->delete();
            event(new UserAction($user->id, 'deleted report', 'User deleted a report.'));

            Log::info('Report  deleted successfully');
            return response()->json(['message'=>"Report  deleted successfully ."]);

        }else{
            Log::info('error id not found');
        }
    }

}
