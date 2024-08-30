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
use App\Http\Controllers\ReportController;
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
    //!super admin delete site:
    Route::middleware('auth:api')->delete('/sites/{id}', [SiteController::class, 'deleteSite']);
    //!super admin add site:
    Route::middleware('auth:api')->post('{workspace}/addSite',[SiteController::class,'addNewSite']);
    //!super admin update site:
    Route::middleware('auth:api')->post('/update_site',[SiteController::class,'updateExistingSite']);
    //! super admin all components : 
    Route::middleware('auth:api')->get('/Components', [ComponentController::class, 'allComponents']);
    //! super admin all incidents : 
    Route::middleware('auth:api')->get('/allIncidents', [IncidentController::class, 'allIncidents']);

    //!reports routes:
    Route::middleware('auth:api')->get('/allReports', [ReportController::class, 'allReports']);
    Route::middleware('auth:api')->post('/add_Report', [ReportController::class, 'addReport']);
    Route::middleware('auth:api')->put('/update_report/{report}', [ReportController::class, 'update']);
    Route::middleware('auth:api')->post('/delete_report', [ReportController::class, 'destroyReport']);


});

// Protect workspaces and related routes with auth:api middleware
Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'workspaces'
], function () {
    Route::apiResource('/', WorkspaceController::class);
    
    //!super admin all workspaces:
    Route::get('/allWorkspaces',[WorkspaceController::class,'allWorkspaces']);

    //!super admin all sites:
    Route::get('/allSites',[SiteController::class,'allSites']);

    //!super admin all buildings:
    Route::get('/allBuildings',[BuildingController::class,'index']);

      //!super admin all projects:
      Route::get('/allProjects', [ProjectController::class, 'allProjects']);
      Route::post('{workspace_id}/addProject', [ProjectController::class, 'storeNewProjectSA']);
      Route::delete('{workspace_id}/Projects/{project_id}', [ProjectController::class, 'destroyProject']);
      Route::put('{workspace_id}/Projects/{project_id}', [ProjectController::class, 'updateProject']);

      //!other roles components for incidents:
      Route::post('/getComponents', [ComponentController::class, 'getComponents']);
    
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
        Route::group([
            'prefix' => '{site}/buildings',
        ], function () {
            Route::group([
                'prefix' => '{building}/components',
            ], function () {
                Route::post('/', [ComponentController::class, 'index']);
            });
        });

    });


    Route::get('/usersIncidents', [IncidentController::class, 'index']);
    Route::post('/incidents/addIncident', [IncidentController::class, 'store']);
    Route::get('/{incident}', [IncidentController::class, 'show']);
    Route::post('/incidents/{incident}', [IncidentController::class, 'updateIncident']);
    Route::post('/deleteIncident', [IncidentController::class, 'destroyIncident']);

//!
    


//!
    Route::group([
        'prefix' => '{workspace}/buildings',
    ], function () {
        
        Route::post('/updateBuilding', [BuildingController::class, 'update']);
        Route::post('/deleteBuilding',[ BuildingController::class, 'destroyBuilding']);
        Route::post('/addBuilding', [BuildingController::class, 'store']);
        Route::get('/{building}', [BuildingController::class, 'show']);

        Route::group([
            'prefix' => '{building}/components',
        ], function () {
            Route::put('/{component}', [ComponentController::class, 'update']);
            Route::post('/deleteComponent',[ ComponentController::class, 'destroyComponent']);
            Route::post('/addComponent', [ComponentController::class, 'store']);
            Route::get('/{component}', [ComponentController::class, 'show']);
            Route::get('/{component}', [ComponentController::class, 'show']);

            // Route::apiResource('/', ComponentController::class);

            Route::group([
                'prefix' => '{component}/incidents',
            ], function () {
                Route::get('/', [IncidentController::class, 'index']);
                Route::post('/addIncident', [IncidentController::class, 'store']);
                Route::get('/{incident}', [IncidentController::class, 'show']);
                Route::post('/updateIncident', [IncidentController::class, 'updateIncident']);
                Route::post('/deleteIncident', [IncidentController::class, 'destroyIncident']);
            });
        });

        Route::group([
            'prefix' => '{building}/incidents',
        ], function () {
            Route::get('/', [IncidentController::class, 'index']);
            Route::post('/addIncident', [IncidentController::class, 'store']);
            Route::get('/{incident}', [IncidentController::class, 'show']);
            Route::post('/updateIncident', [IncidentController::class, 'updateIncident']);
            Route::post('/{incident}', [IncidentController::class, 'destroy']);
        });
    });

    Route::group([
        'prefix' => '{workspace}/projects/{project}/scenarios',
    ], function () {
        // Route::get('/', [ScenarioController::class, 'index']);
        // Route::get('/{scenario}', [ScenarioController::class, 'show']);
        Route::post('/addScenario', [ScenarioController::class, 'store']);
        Route::post('/updateScenario', [ScenarioController::class, 'update']);
        Route::post('/deleteScenario', [ScenarioController::class, 'destroy']);
    });
});
