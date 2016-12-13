<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/oauth/callback', 'OauthController@oauth');
Auth::routes();

Route::get('/home', 'HomeController@index');



Route::get('fire', function () {
    // this fires the event
    event(new App\Events\BroadTest());
    return "event fired";
});
Route::group(['middleware' => 'web:cors'],function (){
    Route::get('username', 'UserController@checkUsername');
    Route::get('phone', 'UserController@checkPhone');
    Route::get('verify', 'AuthController@getVerify');
});


Route::get('test', function () {
    // this checks for the event
    return view('home');
});