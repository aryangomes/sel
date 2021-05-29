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

    Route::resource('/users', 'Api\v1\UserController');

    Route::resource('/lenders', 'Api\v1\LenderController');

    Route::resource('/providers', 'Api\v1\ProviderController');

    Route::resource('/acquisitionTypes', 'Api\v1\AcquisitionTypeController');

    Route::resource('/acquisitions', 'Api\v1\AcquisitionController');

    Route::resource('/collectionTypes', 'Api\v1\CollectionTypeController');

    Route::resource('/collectionCategories', 'Api\v1\CollectionCategoryController');

    Route::resource('/collections', 'Api\v1\CollectionController');

    Route::post('/logout', 'Api\v1\Auth\LoginController@logout');
});
