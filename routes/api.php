<?php

use Illuminate\Support\Facades\Route;

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
return $request->user();
}); */

Route::middleware('auth:api')->group(function () {

    Route::apiResources([
        '/projects'          => "ProjectController",
        '/project-statuses'  => "ProjectStatusController",
        '/project-workflows' => "ProjectWorkflowController",
        '/fields'            => "FieldController",
        '/project-fields'    => "ProjectFieldController",
        '/project-events'    => "ProjectEventController",
        '/attachments'       => "AttachmentController",
        '/dashboards'        => "DashboardController",
    ]);

    Route::get('/project-workflows/project/{project}', 'ProjectWorkflowController@projectWorkflow');
    Route::get('/project-workflows/step-events/{projectWorkflow}', 'ProjectWorkflowController@stepEvents');
    Route::get('/project-fields/project/{project}', 'ProjectFieldController@projectFields');
    Route::patch('/project-fields/{projectField}/up', 'ProjectFieldController@orderUp');
    Route::patch('/project-fields/{projectField}/down', 'ProjectFieldController@orderDown');
    Route::patch('/project-fields/project/{project}/reset', 'ProjectFieldController@orderReset');
    Route::get('/project/{project}/events-on-step/{projectStatus}', 'ProjectController@projectEventsOnStatus');

    Route::get('/attachments/project/{project}', 'AttachmentController@projectAttachments');

    Route::patch('/projects/{project}/up', 'ProjectController@orderUp');
    Route::patch('/projects/{project}/down', 'ProjectController@orderDown');
    Route::patch('/projects/order/reset', 'ProjectController@orderReset');
});

Route::get('/attachments/download/{attachment}', 'AttachmentController@download');


Route::get('/', 'LdapController@show');
Route::post('/login', 'LdapController@login');
Route::get('/ldap', 'LdapController@login');
