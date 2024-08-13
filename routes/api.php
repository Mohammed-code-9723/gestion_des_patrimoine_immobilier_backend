<?php

use App\Http\Controllers\BuildingController;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ScenarioController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [UserController::class, 'login'])->name('login');

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::middleware('auth:api')->get('/all', [UserController::class, 'allUsers']);
    Route::middleware('auth:api')->delete('/users/{id}', [UserController::class, 'deleteUser']);
    Route::middleware('auth:api')->post('/update_user', [UserController::class, 'update']);
    Route::middleware('auth:api')->post('/refresh', [UserController::class, 'refresh']);
    Route::middleware('auth:api')->post('/update_permissions', [UserController::class, 'updateUsersPermissions']);
    Route::middleware('auth:api')->get('/activities', [ActivityController::class, 'allActivities']);
    Route::middleware('auth:api')->post('/register', [UserController::class, 'register'])->name('register');
    Route::middleware('auth:api')->delete('/workspaces/{id}', [WorkspaceController::class, 'deleteWorkspace']);
    Route::middleware('auth:api')->post('/workspaces/{id}', [WorkspaceController::class, 'updateWorkspace']);
    Route::middleware('auth:api')->post('/users/{id}/addWorkspace', [WorkspaceController::class, 'addWorkspace']);
    Route::middleware('auth:api')->delete('/sites/{id}', [SiteController::class, 'deleteSite']);
    //!super admin add site:
    Route::middleware('auth:api')->post('{workspace}/addSite',[SiteController::class,'addNewSite']);
    //!super admin update site:
    Route::middleware('auth:api')->post('/update_site',[SiteController::class,'updateExistingSite']);
    Route::middleware('auth:api')->get('/Components', [ComponentController::class, 'allComponents']);

});

// Protect workspaces and related routes with auth:api middleware
Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'workspaces'
], function () {
    Route::apiResource('/', WorkspaceController::class);
    
    //!super admin all workspaces:
    Route::get('/allWorkspaces',[WorkspaceController::class,'allWorkspaces']);

    Route::group([
        'prefix' => '{workspace}/projects',
    ], function () {
        Route::get('/', [ProjectController::class, 'index']);  //* worked
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

    //!super admin all sites:
    Route::get('/allSites',[SiteController::class,'allSites']);

    //!super admin all buildings:
    Route::get('/allBuildings',[BuildingController::class,'index']);

    //!super admin all projects:
    Route::get('/allProjects', [ProjectController::class, 'allProjects']);
    Route::post('{workspace_id}/addProject', [ProjectController::class, 'storeNewProjectSA']);
    Route::delete('{workspace_id}/Projects/{project_id}', [ProjectController::class, 'destroyProject']);
    Route::put('{workspace_id}/Projects/{project_id}', [ProjectController::class, 'updateProject']);


    Route::group([
        'prefix' => '{workspace}/buildings',
    ], function () {
        Route::apiResource('/', BuildingController::class);

        Route::group([
            'prefix' => '{building}/components',
        ], function () {
            Route::apiResource('/', ComponentController::class);
            Route::group([
                'prefix' => '{component}/incidents',
            ], function () {
                Route::get('/', [IncidentController::class, 'index']);
                Route::post('/', [IncidentController::class, 'store']);
                Route::get('/{incident}', [IncidentController::class, 'show']);
                Route::put('/{incident}', [IncidentController::class, 'update']);
                Route::delete('/{incident}', [IncidentController::class, 'destroy']);
            });
        });

        Route::group([
            'prefix' => '{building}/incidents',
        ], function () {
            Route::get('/', [IncidentController::class, 'index']);
            Route::post('/', [IncidentController::class, 'store']);
            Route::get('/{incident}', [IncidentController::class, 'show']);
            Route::put('/{incident}', [IncidentController::class, 'update']);
            Route::delete('/{incident}', [IncidentController::class, 'destroy']);
        });
    });

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
