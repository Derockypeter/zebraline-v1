<?php

use App\Http\Controllers\SocialiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


    Route::get('/auth/login', function () {
        #TODO: Move to a controller RoutingController
        if (!auth()->check()) {
            return view('auth.login');
        }else {
            return redirect('client/dashboard');
        }
    })->name('login');
    
    Route::get('/auth/signup', function () {
        if (!auth()->check()) {
            return view('auth.register');
        }else {
            return redirect('client/dashboard');
        }
    });

Route::get('/client/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


Route::prefix('auth')->group(function () {
    Route::get('/{driver}', [SocialiteController::class, 'redirect'])->name('google-auth');
    Route::get('/{driver}/callback', [SocialiteController::class, 'callback']);

    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
    Route::post('/ante_login', [App\Http\Controllers\AuthController::class, 'login']);
    Route::post('/signup', [App\Http\Controllers\AuthController::class, 'signup']);
   
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
});

Route::get('/onboarding/choose-domain-n-email', function() {
    return view('onboarding.choose-domain');
})->middleware(['auth']);

Route::post('tenant_make', [App\Http\Controllers\TenantController::class, 'create']);
