<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\DomainCheckerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/verify_otp', [AuthController::class, 'verifyOtp']);
Route::post('/save_n_send_otp', [AuthController::class, 'savenSendOtp']);

Route::get('/checklocaldomain', [DomainCheckerController::class, 'checkLocalDomain']);
Route::post('domain/check', [DomainCheckerController::class, 'check']);

Route::get('theme', [ThemeController::class, 'index']);


Route::group(['prefix' => 'v1'], function(){
    // For when user trys to create domain, user is not authenticated yet;
    //    TODO: Remember to create another endpoint for storing payment data, when logged in
    Route::get('/user/setup-intent', [StripeController::class, 'getSetupIntent']);
    Route::post('/user/payments', [StripeController::class, 'postPaymentMethods']);
    Route::post('/user/remove-payment', [StripeController::class, 'removePaymentMethod']);
});
