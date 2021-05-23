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


Route::prefix('login')->group(function () {
    Route::post('/admin', 'Api\v1\Auth\LoginController@loginUserAdministrator');
    Route::post('/', 'Api\v1\Auth\LoginController@loginUserNotAdministrator');
});


Route::middleware('auth:api')->group(function () {

    Route::resource('/user', 'Api\v1\UserController');

    Route::resource('/lender', 'Api\v1\LenderController');

    Route::resource('/provider', 'Api\v1\ProviderController');

    Route::resource('/acquisitionType', 'Api\v1\AcquisitionTypeController');

    Route::resource('/acquisition', 'Api\v1\AcquisitionController');

    Route::post('/logout', 'Api\v1\Auth\LoginController@logout');
});
