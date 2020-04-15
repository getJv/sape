<?php

use Illuminate\Support\Facades\Route;

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
return $request->user();
}); */

Route::middleware('auth:api')->group(function () {

    Route::get('auth-user', 'AuthUserController@show');

    Route::apiResources([
        '/projects'          => "ProjectController",
        '/project-statuses'  => "ProjectStatusController",
        '/project-workflows' => "ProjectWorkflowController",
        '/fields'            => "FieldController",
        '/project-fields'    => "ProjectFieldController",
        '/project-events'    => "ProjectEventController",

    ]);

    Route::get('/project-workflows/project/{project}', 'ProjectWorkflowController@projectWorkflow');
    Route::get('/project-workflows/step-events/{projectWorkflow}', 'ProjectWorkflowController@stepEvents');
    Route::get('/project-fields/project/{project}', 'ProjectFieldController@projectFields');
    Route::get('/project/{project}/events-on-step/{projectStatus}', 'ProjectController@projectEventsOnStatus');
});
