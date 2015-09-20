<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group([
    'prefix' => 'api/{resource}',
    'namespace' => 'Api'
], function() {
    Route::get('/', 'Controller@index');
    Route::post('/', 'Controller@store');
    Route::get('/{id}', 'Controller@show');
    Route::put('/{id}', 'Controller@update');
    Route::delete('/{id}', 'Controller@destroy');
});

Route::resource(
    'resources',
    'ResourcesController',
    ['except' => ['edit', 'update']]
);
