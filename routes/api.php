<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\HouseController;
use App\Http\Controllers\API\HouseholderController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\PaymentTypeController;
use App\Http\Controllers\API\ResidentController;
use App\Http\Controllers\API\SettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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

Route::get('/', function () {
    $clearcache = Artisan::call('cache:clear');
    $clearview = Artisan::call('view:clear');
    $clearconfig = Artisan::call('config:cache');
    return 'Beon Test API <br/>' . app()->version();
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('jwt.verify')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::middleware('jwt.verify')->group(function () {
    Route::apiResource('resident', ResidentController::class);
    Route::apiResource('house', HouseController::class);
    Route::apiResource('payment-type', PaymentTypeController::class);
    Route::apiResource('householder', HouseholderController::class);
    Route::group(['prefix' => 'payment'], function () {
        Route::get('/', [PaymentController::class, 'index']);
        Route::post('/', [PaymentController::class, 'store']);
    });
});
