<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\SiteVisibilityController;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomainOrSubdomain;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomainOrSubdomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/', [App\Http\Controllers\TenantController::class, 'template'])->name('userHome');
    Route::get('/password', function () {
        return view('auth.passwordentry');
    })->name('password.entry');
    Route::post('/password-check', [SiteVisibilityController::class, 'checkPassword'])->name('password.check');

});
