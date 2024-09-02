<?php

namespace App\Http\Controllers;

use App\Models\Component;
use Illuminate\Http\Request;
use App\Events\UserAction;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Building;

class ComponentController extends Controller
{
    public function allComponents()
    {
        $allComponents=Component::with('incidents')->get();
        return response()->json(['allComponents'=>$allComponents]);
    }


    public function index(Request $request)
    {
        Log::info('building id to get its components:');
        Log::info($request->building_id);

        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $allComponents=Building::find($request->building_id)->components()->with('incidents')->get();
        return response()->json(['allComponents'=>$allComponents]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:255|unique:components,code',
            'name' => 'required|string|max:255',
            'quantity' => 'required|numeric',
            'unit' => 'required|string|max:255',
            'last_rehabilitation_year' => 'nullable|string|max:255',
            'condition' => 'required|in:C1,C2,C3,C4',
            'severity_max' => 'required|in:S1,S2,S3,S4',
            'risk_level' => 'required|in:R1,R2,R3,R4',
            'description' => 'nullable|string',
            'severity_safety' => 'nullable|in:S1,S2,S3,S4',
            'severity_operations' => 'nullable|in:S1,S2,S3,S4',
            'severity_work_conditions' => 'nullable|in:S1,S2,S3,S4',
            'severity_environment' => 'nullable|in:S1,S2,S3,S4',
            'severity_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'building_id' => 'required|exists:buildings,id',
            'characteristics' => 'nullable|string',
        ]);


        
        if ($request->hasFile('severity_image')) {
            $file = $request->file('severity_image');
            $fileName = time() . '_' . $file->getClientOriginalName(); 
            $file->move(public_path('images'), $fileName);
            $validatedData['severity_image'] = "images/{$fileName}";
        }

        Component::create($validatedData);
        log::info($validatedData);

        $user = JWTAuth::parseToken()->authenticate();

        if ($user) {
            event(new UserAction($user->id, 'created_component', 'User created a new component'));
        }

        return response()->json(['message'=>'Component '.$validatedData['name'].' updated successfully.']);
    }

    public function show(Component $component)
    {
        return $component;
    }

    public function update(Request $request)
    {
        
        Log::info($request->all());
        $validatedData = $request->validate([
            // 'code' => 'required|string|max:255|unique:components,code',
            'name' => 'required|string|max:255',
            'quantity' => 'required',
            'unit' => 'required|string|max:255',
            'last_rehabilitation_year' => 'nullable|string|max:255',
            'condition' => 'required|in:C1,C2,C3,C4',
            'severity_max' => 'required|in:S1,S2,S3,S4',
            'risk_level' => 'required|in:R1,R2,R3,R4',
            'description' => 'nullable|string',
            'severity_safety' => 'nullable|in:S1,S2,S3,S4',
            'severity_operations' => 'nullable|in:S1,S2,S3,S4',
            'severity_work_conditions' => 'nullable|in:S1,S2,S3,S4',
            'severity_environment' => 'nullable|in:S1,S2,S3,S4',
            'severity_image' => 'nullable|string|max:255',
            'building_id' => 'required|exists:buildings,id',
            'characteristics' => 'nullable|string',
        ]);
        
        $component = Component::find($request->id);

        // Log::info("Code:");
        // Log::info($validatedData['code']);

        // $component->code=$validatedData['code'];
        $component->name=$validatedData['name'];
        $component->quantity=(float)$validatedData['quantity'];
        $component->unit=$validatedData['unit'];
        $component->last_rehabilitation_year=$validatedData['last_rehabilitation_year'];
        $component->condition=$validatedData['condition'];
        $component->severity_max=$validatedData['severity_max'];
        $component->risk_level=$validatedData['risk_level'];
        $component->description=$validatedData['description'];
        $component->severity_safety=$validatedData['severity_safety'];
        $component->severity_operations=$validatedData['severity_operations'];
        $component->severity_work_conditions=$validatedData['severity_work_conditions'];
        $component->severity_environment=$validatedData['severity_environment'];
        $component->severity_image=$validatedData['severity_image'];
        $component->building_id=$validatedData['building_id'];
        $component->characteristics=$validatedData['characteristics'];
        $component->save();

        $user = JWTAuth::parseToken()->authenticate();

        if ($user) {
            event(new UserAction($user->id, 'updated_component', 'User updated a component'));
        }

        return response()->json(['message'=>'Component '.$validatedData['name'].' updated successfully.']);
    }

    public function destroyComponent(Request $request)
    {
        Log::info('deleted id :');
        Log::info($request->all());
        
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        Component::find($request->id)->delete();

        event(new UserAction($user->id, 'deleted_component', 'User deleted a component'));

        return response()->json(['message'=>'Component deleted successfully.']);
    }
}
