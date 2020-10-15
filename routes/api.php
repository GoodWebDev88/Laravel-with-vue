<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@registerUser');
    Route::post('project', 'ProjectController@addProject');
    Route::get('projects', 'ProjectController@getProjects');
    Route::get('users', 'AuthController@getUsers');
    Route::post('addevent', 'EventController@addEvent');
    Route::post('editevent', 'EventController@updateEvent');
    Route::get('events', 'EventController@getEvents');
    Route::delete('events/{id}', 'EventController@deleteEvent');
    Route::delete('projects/{id}', 'ProjectController@deleteProject');
    Route::post('editproject', 'ProjectController@updateProject');
    Route::post('externalEvent', 'ExternalController@addExternalEvent');
    Route::post('editexternal', 'ExternalController@updateExternal');
//    Route::post('logout', 'AuthController@logout');
//    Route::post('refresh', 'AuthController@refresh');
//    Route::post('me', 'AuthController@me');
});
