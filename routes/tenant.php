<?php

declare(strict_types=1);

use App\Http\Controllers\Jara\AuthController;
use App\Http\Controllers\Tenant\ArticleController;
use App\Http\Controllers\Tenant\ConsultationController;
use App\Http\Controllers\Tenant\JaraDomainController;
use App\Http\Controllers\Tenant\MessageController;
use App\Http\Controllers\Tenant\PromoCodeController;
use App\Http\Controllers\Tenant\PurchasedAppController;
use App\Http\Controllers\Tenant\RoleController;
use App\Http\Controllers\Tenant\SiteBuilder\PageController;
use App\Http\Controllers\Tenant\SiteBuilder\PolicyPageController;
use App\Http\Controllers\Tenant\SiteBuilder\SectionController;
use App\Http\Controllers\Tenant\SiteBuilder\ThemeCustomizationController;
use App\Http\Controllers\Tenant\TenantDataController;
use App\Http\Controllers\Tenant\UserController;
use App\Http\Controllers\Tenant\WebSettings\BasicInfoController;
use App\Http\Controllers\Tenant\WebSettings\MaintenanceModeController;
use App\Http\Controllers\Tenant\WebSettings\SocialMediaController;
use App\Models\Tenant\PurchasedApp;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomainOrSubdomain;
use Stancl\Tenancy\Middleware\InitializeTenancyByRequestData;

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

Route::prefix('api')->middleware([
    InitializeTenancyByRequestData::class,
    //    PreventAccessFromCentralDomains::class,
])->group(function () {
    //    Public routes

    //    Consultations
    Route::apiResource('consultations', ConsultationController::class)->only('index');

    //    Messages
    Route::apiResource('messages', MessageController::class)->only('store');


    //    Protected routes for authenticated users only
    Route::middleware(['auth:sanctum'])->group(function () {
        //        Auth
        Route::post('logout', [AuthController::class, 'logout']);

        //        User
        Route::get('users/authenticated', [UserController::class, 'show_authenticated']);
        Route::post('users/update-profile-picture', [UserController::class, 'update_profile_picture']);
        Route::delete('users/delete-profile-picture', [UserController::class, 'delete_profile_picture']);
        Route::post('users/update-basic-info', [UserController::class, 'update_basic_info']);
        Route::post('users/update-password', [UserController::class, 'update_password']);
        Route::apiResource('users', UserController::class);

        //        Roles
        Route::apiResource('roles', RoleController::class);

        //        Consultations
        Route::apiResource('consultations', ConsultationController::class);

        //        Messages
        Route::apiResource('messages', MessageController::class)->except('store');
        Route::post('messages/search', [MessageController::class, 'search']);
        Route::post('messages/sort', [MessageController::class, 'sort']);
        Route::post('messages/filter', [MessageController::class, 'filter']);
        Route::post('messages/destroy-bulk', [MessageController::class, 'destroy_bulk']);

        //        Promocodes
        Route::apiResource('promocodes', PromoCodeController::class);
        Route::post('promocodes/search', [PromoCodeController::class, 'search']);
        Route::post('promocodes/sort', [PromoCodeController::class, 'sort']);
        Route::post('promocodes/filter', [PromoCodeController::class, 'filter']);
        Route::post('promocodes/destroy-bulk', [PromoCodeController::class, 'destroy_bulk']);

        //        Site Builder
        //        Themes
        Route::apiResource('theme-customizations', ThemeCustomizationController::class);
        //        Pages
        Route::apiResource('pages', PageController::class);
        Route::post('pages/search', [PageController::class, 'search']);
        Route::post('pages/sort', [PageController::class, 'sort']);
        Route::post('pages/filter', [PageController::class, 'filter']);
        Route::post('pages/destroy-bulk', [PageController::class, 'destroy_bulk']);
        Route::apiResource('policies-pages', PolicyPageController::class);

        //        Sections
        Route::apiResource('sections', SectionController::class);

        //    Web Settings
        Route::apiResource('web-settings/basic-info', BasicInfoController::class);
        Route::apiResource('web-settings/social-media', SocialMediaController::class);
        Route::apiResource('web-settings/maintenance-mode', MaintenanceModeController::class);

        //    Purchased Apps
        Route::apiResource('purchased-apps', PurchasedAppController::class);
        Route::post('purchased-apps/zoom/store-access-token', [PurchasedAppController::class, 'store_zoom_access_token']);
        Route::post('purchased-apps/zoom/create-new-meeting', [PurchasedAppController::class, 'create_new_zoom_meeting']);
        //    Articles
        Route::apiResource('articles', ArticleController::class);


        //    Jara Domains
        Route::post('jara-domains/check-availability', [JaraDomainController::class, 'check_availability']);
    });

    Route::middleware([InitializeTenancyByDomainOrSubdomain::class,
    ])->group(function () {
        //    Get Website Data
        Route::get('tenant-data', [TenantDataController::class, 'get_data']);
    });

});

