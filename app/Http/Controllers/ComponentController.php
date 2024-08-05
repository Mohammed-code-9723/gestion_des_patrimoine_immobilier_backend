<?php

namespace App\Http\Controllers;

use App\Models\Component;
use Illuminate\Http\Request;
use App\Events\UserAction;
use Tymon\JWTAuth\Facades\JWTAuth;


class ComponentController extends Controller
{
    public function index()
    {
        return Component::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'unit' => 'required|string|max:255',
            'last_rehabilitation_year' => 'required|integer',
            'condition' => 'required|string|max:255',
            'severity_max' => 'required|integer',
            'risk_level' => 'required|string|max:255',
            'building_id' => 'required|exists:buildings,id',
        ]);

        $component = Component::create($validatedData);

        $user = JWTAuth::parseToken()->authenticate();

        if ($user) {
            event(new UserAction($user->id, 'created_component', 'User created a new component'));
        }

        return $component;
    }

    public function show(Component $component)
    {
        return $component;
    }

    public function update(Request $request, Component $component)
    {
        $validatedData = $request->validate([
            'code' => 'string|max:255',
            'name' => 'string|max:255',
            'type' => 'string|max:255',
            'quantity' => 'integer',
            'unit' => 'string|max:255',
            'last_rehabilitation_year' => 'integer',
            'condition' => 'string|max:255',
            'severity_max' => 'integer',
            'risk_level' => 'string|max:255',
            'building_id' => 'exists:buildings,id',
        ]);

        $component->update($validatedData);

        $user = JWTAuth::parseToken()->authenticate();

        if ($user) {
            event(new UserAction($user->id, 'updated_component', 'User updated a component'));
        }

        return $component;
    }

    public function destroy(Component $component)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $component->delete();

        event(new UserAction($user->id, 'deleted_component', 'User deleted a component'));

        return response()->json(['message'=>'Component deleted successfully.']);
    }
}
