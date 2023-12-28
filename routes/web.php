<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;


use App\Http\Controllers\StripeController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\Tenant\ImageController;
use App\Http\Controllers\Tenant\MetaSEOController;
use App\Http\Controllers\Tenant\ProductController;
use App\Http\Controllers\Tenant\SitedataController;
use App\Http\Controllers\Tenant\PaymentGatewayController;
use App\Http\Controllers\Tenant\SiteVisibilityController;
use App\Http\Controllers\Tenant\PaymentBillingAddressController;

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
    return view('client.onboarding');
})->middleware(['auth']);

Route::get('/client/dashboard', function () {
    return view('client.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/client/product', function () {
    return view('client.product');
})->middleware(['auth'])->name('product');

Route::get('/client/design', function () {
    return view('client.design');
})->middleware(['auth'])->name('client.design');

Route::get('/client/payment', function () {
    return view('client.payment');
})->middleware(['auth'])->name('client.payment');


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
Route::post('site_visibility', [SiteVisibilityController::class, 'storeOrUpdate']);





Route::post('/product', [ProductController::class, 'store']);
Route::put('/product/{id}', [ProductController::class, 'update']);
Route::get('/product', [ProductController::class, 'index']);
Route::post('/unlink_img', [ImageController::class, 'unlinkImage']);
Route::post('/billing', [PaymentBillingAddressController::class, 'store']);

Route::apiResource('/payment', PaymentGatewayController::class);
ROute::post('/payment-default', [PaymentGatewayController::class, 'updateDefault']);


Route::group(['prefix' => 'v1'], function(){
    // For when user trys to create domain, user is not authenticated yet;
    //    TODO: Remember to create another endpoint for storing payment data, when logged in
    Route::get('/user/setup-intent', [StripeController::class, 'getSetupIntent'])->middleware('auth');
    Route::post('/user/payments', [StripeController::class, 'postPaymentMethods'])->middleware('auth');
    Route::post('/user/remove-payment', [StripeController::class, 'removePaymentMethod'])->middleware('auth');
});




