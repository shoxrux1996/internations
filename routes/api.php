<?php

use Illuminate\Support\Facades\Route;

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

Route::prefix('auth')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::post('login', 'Admin\AuthController@login');
        Route::get('profile', 'Admin\AuthController@user')->middleware('auth:api');
    });
});

Route::prefix('admin')->middleware(['auth:api'])->group(function () {
    Route::resource('users', 'Admin\UserController');
    Route::resource('groups', 'Admin\GroupController');
});
