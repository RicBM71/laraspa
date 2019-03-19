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


Route::group([
    //'as' => '.admin' ver php artisan r:l para ver problema admin.admin.
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => 'jwt.auth'],
    function (){
        Route::resource('users', 'UsersController', ['as' => 'admin']);
        Route::resource('roles', 'RolesController', ['as' => 'admin']);
        Route::resource('permissions', 'PermissionsController', ['except'=>'show', 'as' => 'admin']);

        Route::middleware('role:Root|Admin')
            ->put('users/{user}/roles','UsersRolesController@update');
        Route::middleware('role:Root|Admin')
			->put('users/{user}/permissions','UsersPermissionsController@update');


    }
);

Route::any('{all}', function () {
    return view('app');
})->where(['all' => '.*']);

