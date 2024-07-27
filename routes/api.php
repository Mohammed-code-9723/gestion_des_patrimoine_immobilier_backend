<?php

use App\Http\Controllers\IncidentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ScenarioController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/register', [UserController::class, 'register'])->name('register');

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::middleware('auth:api')->get('/all', [UserController::class, 'allUsers']);
    Route::middleware('auth:api')->post('/refresh', [UserController::class, 'refresh']);
});



Route::group([
    'prefix' => 'workspaces',
    'middleware' => 'auth:api'
], function () {
    
    Route::apiResource('workspaces', WorkspaceController::class);
    
    Route::group([
        'prefix' => '{workspace}/projects',
    ], function () {
        Route::get('/', [ProjectController::class, 'index']);
        Route::post('/', [ProjectController::class, 'store']);
        Route::get('/{project}', [ProjectController::class, 'show']);
        Route::put('/{project}', [ProjectController::class, 'update']);
        Route::delete('/{project}', [ProjectController::class, 'destroy']);
    });

    Route::group([
        'prefix' => '{workspace}/sites',
    ], function () {
        Route::get('/', [SiteController::class, 'index']);
        Route::post('/', [SiteController::class, 'store']);
        Route::get('/{site}', [SiteController::class, 'show']);
        Route::put('/{site}', [SiteController::class, 'update']);
        Route::delete('/{site}', [SiteController::class, 'destroy']);
    });

    // Incident routes
    Route::group([
        'prefix' => '{workspace}/buildings/{building}/incidents',
    ], function () {
        Route::get('/', [IncidentController::class, 'index']);
        Route::post('/', [IncidentController::class, 'store']);
        Route::get('/{incident}', [IncidentController::class, 'show']);
        Route::put('/{incident}', [IncidentController::class, 'update']);
        Route::delete('/{incident}', [IncidentController::class, 'destroy']);
    });

    // Scenario routes
    Route::group([
        'prefix' => '{workspace}/projects/{project}/scenarios',
    ], function () {
        Route::get('/', [ScenarioController::class, 'index']);
        Route::post('/', [ScenarioController::class, 'store']);
        Route::get('/{scenario}', [ScenarioController::class, 'show']);
        Route::put('/{scenario}', [ScenarioController::class, 'update']);
        Route::delete('/{scenario}', [ScenarioController::class, 'destroy']);
    });
});