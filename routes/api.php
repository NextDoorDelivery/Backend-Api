<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerVerificationController;
use App\Http\Controllers\VerificationStatusController;
use App\Http\Controllers\CustomerProfileController;

/**
 * Middleware list:
 * auth:api -> guards the route using email and password validation for admin and registrar device only.
 * mobileAuth -> guards the route using mobile_token and device_uuid validation for customer mobile devices.
 */


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * This group handles user login/authentication.
 */
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);

});

/**
 * This group handles customer registration and verification.
 */
Route::group([

    'middleware' => 'api',
    'prefix' => 'customer'

], function ($router) {
    // Unprotected route.
    Route::post('phonenumber', [CustomerVerificationController::class, 'sendPhoneNumber']);
    // Unprotected route.
    Route::post('verifycode', [CustomerVerificationController::class, 'verifyCode']);
    Route::get('phonenumber', [VerificationStatusController::class, 'getPhoneNumber'])->middleware('auth:api');
    Route::patch('phonenumber', [VerificationStatusController::class, 'updateVerificationStatus'])->middleware('auth:api');

    // Customer profile.
    Route::post('profile', [CustomerProfileController::class, 'createCustomer'])->middleware('mobileAuth');
});

