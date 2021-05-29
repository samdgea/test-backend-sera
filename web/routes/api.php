<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {

    Route::prefix('customer')->group(function (){
        Route::post('/', [\App\Http\Controllers\APIController::class, 'storeNewCustomer']);
        Route::get('{id}', [\App\Http\Controllers\APIController::class, 'getCustomer']);
        Route::delete('{id}', [\App\Http\Controllers\APIController::class, 'deleteCustomer']);
    });

    Route::get('customers', [\App\Http\Controllers\APIController::class, 'getAllCustomers']);
});
