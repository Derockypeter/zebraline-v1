<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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
