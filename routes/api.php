<?php

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

Route::post('authenticate', 'AuthenticateController@authenticate');

// Route::group(['middleware' => 'jwt.auth'], function()
// {
//     Route::get('user', 'UserController@show');
//     Route::post('user/profile/update', 'UserController@updateProfile');
//     Route::post('user/password/update', 'UserController@updatePassword');
// });

Route::group([
    //'as' => '.admin' ver php artisan r:l para ver problema admin.admin.
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => 'jwt.auth'],
    function (){
        Route::resource('users', 'UsersController', ['as' => 'admin']);
        // Route::resource('roles', 'RolesController', ['as' => 'admin']);
        // Route::resource('permissions', 'PermissionsController', ['except'=>'show', 'as' => 'admin']);

        // Route::middleware('role:Admin')
        //     ->put('users/{user}/roles','UsersRolesController@update');
        // Route::middleware('role:Admin')
		// 	->put('users/{user}/permissions','UsersPermissionsController@update');


    }
);
