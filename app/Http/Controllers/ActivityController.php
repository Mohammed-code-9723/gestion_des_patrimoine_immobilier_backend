<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Events\UserAction;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;


class ActivityController extends Controller
{
    public function allActivities()
    {
        $activities = Activity::all();
        return response()->json($activities);
    }

    public function show($dateActivity, $activityId)
    {
        
        $parsedDate = Carbon::parse($dateActivity);

        $activity = Activity::whereDate('created_at', $parsedDate->toDateString())
                    ->whereTime('created_at', $parsedDate->toTimeString())
                    ->where('id', $activityId)
                    ->first(); 

        if (!$activity) {
            return response()->json(['message' => 'Activity not found'], 404);
        }

        return response()->json($activity);
    }

    public function destroy($activityId)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $activity = Activity::find($activityId);

        if (!$activity) {
            return response()->json(['message' => 'Activity not found'], 404);
        }

        $activity->delete();

        event(new UserAction($user->id, 'deleted_activity', 'User deleted activity ' . $activityId));

        return response()->json(['message' => 'Activity deleted']);
    }
}
