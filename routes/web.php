<?php

use App\Http\Controllers\Tenant\SitedataController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\Tenant\MetaSEOController;
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


Route::get('/onboarding/choose-domain-n-email', function() {
    return view('onboarding');
})->middleware(['auth']);

Route::get('/client/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/client/product', function () {
    return view('client.product');
})->middleware(['auth'])->name('product');

Route::get('/client/design', function () {
    return view('client.design');
})->middleware(['auth'])->name('client.design');


Route::prefix('auth')->group(function () {
    Route::get('/{driver}', [SocialiteController::class, 'redirect'])->name('google-auth');
    Route::get('/{driver}/callback', [SocialiteController::class, 'callback']);

    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
    Route::post('/ante_login', [App\Http\Controllers\AuthController::class, 'login']);
    Route::post('/signup', [App\Http\Controllers\AuthController::class, 'signup']);
   
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
});


Route::post('tenant_make', [App\Http\Controllers\TenantController::class, 'create']);
Route::post('create_template_ids', [App\Http\Controllers\TenantController::class, 'createComponentsForTenant']);
Route::get('getdomainName', [App\Http\Controllers\TenantController::class, 'getdomainName']);
Route::post('font_color', [SiteDataController::class, 'storeFontColor']);
Route::post('favicon_logo', [SiteDataController::class, 'storeFaviconLogo']);
Route::post('other_settings', [SiteDataController::class, 'storeOthers']);
Route::post('meta_data', [MetaSEOController::class, 'store']);



