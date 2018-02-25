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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/devices', 'DeviceController@index');

Route::get('/devices/{id}', 'DeviceController@show');

Route::post('/devices', 'DeviceController@store');

Route::put('/devices/{id}', 'DeviceController@update');

Route::delete('/devices/{id}', 'DeviceController@destroy');
