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

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController'
]);

Route::get('/', 'HomeController@index');

# Users
Route::resource('users', 'UsersController');
Route::resource('roles', 'RolesController');

# API v1
Route::group(['middleware' => 'cors', 'domain' => 'api.material.cookapp.club', 'prefix' => 'v1'], function() {
    Route::resource('users', 'Api\V1\UsersController');
    Route::resource('roles', 'Api\V1\RolesController');

    /*Route::get('users', 'Api\V1\UsersController@index');
    Route::post('users', 'Api\V1\UsersController@store');
    Route::get('users/{id}', 'Api\V1\UsersController@show');
    Route::put('users/{id}', 'Api\V1\UsersController@update');
    Route::delete('users/{id}', 'Api\V1\UsersController@destroy');*/


});