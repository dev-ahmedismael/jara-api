<?php

declare(strict_types=1);

use App\Http\Controllers\Central\Article\ArticleController;
use App\Http\Controllers\Tenant\App\AppController;
use App\Http\Controllers\Tenant\Consultation\ConsultationController;
use App\Http\Controllers\Tenant\Customer\CustomerController;
use App\Http\Controllers\Tenant\Order\ChatMessageController;
use App\Http\Controllers\Tenant\Order\ChatroomController;
use App\Http\Controllers\Tenant\Order\OrderController;
use App\Http\Controllers\Tenant\Promocode\PromocodeController;
use App\Http\Controllers\Tenant\RolePermission\RolePermissionController;
use App\Http\Controllers\Tenant\Stats\TenantStatsController;
use App\Http\Controllers\Tenant\Theme\ThemeController;
use App\Http\Controllers\Tenant\User\UserController;
use App\Http\Controllers\Tenant\WebsiteSettings\BasicInfoController;
use App\Http\Controllers\Tenant\WebsiteSettings\DomainSettingController;
use App\Http\Controllers\Tenant\WebsiteSettings\MaintenanceModeController;
use App\Http\Controllers\Tenant\WebsiteSettings\PageController;
use App\Http\Controllers\Tenant\WebsiteSettings\SocialMediaUrlController;
use Illuminate\Support\Facades\Route;
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
])->group(function () {

    //    Stats
    Route::get('tenant-stats', [TenantStatsController::class, 'index']);

    //    Website Settings
    Route::prefix('website-settings')->group(function () {
        Route::apiResource('domain-settings', DomainsettingController::class);
        Route::post('check-subdomain-availability', [DomainsettingController::class, 'check_subdomain_availability']);
        Route::post('check-domain-availability', [DomainsettingController::class, 'check_domain_availability']);
        Route::apiResource('basic-info', BasicInfoController::class);
        Route::apiResource('social-media', SocialMediaUrlController::class);
        Route::apiResource('maintenance-mode', MaintenanceModeController::class);
        Route::apiResource('pages', PageController::class);
        Route::post('pages/{path}', [PageController::class, 'update']);
     });

    //    Promo Codes
    Route::apiResource('promocodes', PromocodeController::class);

    //    Roles and Permissions
     Route::get('all-roles', [RolePermissionController::class, 'all_roles']);
     Route::apiResource('roles', RolePermissionController::class);

    //    Users
    Route::apiResource('users', UserController::class);

    //    Articles
    Route::apiResource('articles', ArticleController::class);
    Route::post('articles/{id}', [ArticleController::class, 'update']);

    //    Customers
    Route::apiResource('customers', CustomerController::class);

    //    Apps
    Route::apiResource('installed-apps', AppController::class);
    Route::post('installed-apps/zoom/store-tokens', [AppController::class, 'store_zoom_tokens']);

    //    Themes
    Route::post('themes/install', [ThemeController::class, 'install']);
    Route::get('installed-theme', [ThemeController::class, 'installed_theme']);
    Route::post('themes/customize', [ThemeController::class, 'customize']);
    Route::post('themes/reset', [ThemeController::class, 'reset']);

    //    Consultations
    Route::apiResource('consultations', ConsultationController::class);
    Route::post('consultations/{id}', [ConsultationController::class, 'update']);

    //  Orders
    Route::get('orders', [OrderController::class, 'index']);
    Route::put('orders/{id}', [OrderController::class, 'update']);

//    Chat Room
    Route::get('chatrooms', [ChatroomController::class, 'index']);
    Route::get('chatrooms/{id}', [ChatroomController::class, 'show']);
    Route::post('chat-messages', [ChatMessageController::class, 'store']);

});


