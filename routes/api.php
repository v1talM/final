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

Route::get('/users', 'UserController@getAllUsers')->middleware('auth:api');

Route::post('/register', 'AuthController@register')->middleware('api','cors');
Route::post('/user', 'UserController@delUserById')->middleware('auth:api','cors');