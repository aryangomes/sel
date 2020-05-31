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

use Illuminate\Support\Facades\Route;

// Route::post('login', 'Api\v1\Auth\LoginController@authenticate');

Route::prefix('login')->group(function () {
    Route::post('/admin', 'Api\v1\Auth\LoginController@loginUserAdministrator');
});

Route::middleware('auth:api')->group(function () {
    Route::get('/', function () {
        // Uses first & second Middleware
    });

});

