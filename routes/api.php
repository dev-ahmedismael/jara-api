<?php

use App\Http\Controllers\Central\App\AppController;
use App\Http\Controllers\Central\Article\ArticleController;
use App\Http\Controllers\Central\Authentication\AuthController;
use App\Http\Controllers\Central\Promocode\PromocodeController;
use App\Http\Controllers\Central\Setting\SettingController;
use App\Http\Controllers\Central\Stats\StatsController;
use App\Http\Controllers\Central\Tenant\TenantController;
use App\Http\Controllers\Central\Theme\ThemeController;
use App\Http\Controllers\Central\Transaction\TransactionController;
use App\Http\Controllers\Tenant\Consultation\ConsultationController;
use App\Http\Controllers\Tenant\Customer\CustomerController;
use App\Http\Controllers\Tenant\Order\ChatMessageController;
use App\Http\Controllers\Tenant\Order\ChatroomController;
use App\Http\Controllers\Tenant\Order\OrderController;
use App\Http\Controllers\Tenant\TenantWebsite\TenantWebsiteController;
use App\Http\Middleware\IdentifyTenant;
use Illuminate\Support\Facades\Route;


    Route::post('central/login', [AuthController::class, 'login']);
    Route::post('central/logout', [AuthController::class, 'logout']);
    Route::get('stats', [StatsController::class, 'index']);
    Route::apiResource('tenants', TenantController::class );

    Route::apiResource('settings', SettingController::class);
    Route::apiResource('transactions', TransactionController::class);
    Route::apiResource('apps', AppController::class);
    Route::apiResource('themes', ThemeController::class);
    Route::apiResource('central/articles', ArticleController::class);
    Route::post('central/articles/{id}', [ArticleController::class, 'update']);
    Route::apiResource('central/promocodes', PromocodeController::class);

    // TENANT WEBSITE ROUTES
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::middleware([IdentifyTenant::class])->group(function () {
        // Tenant Website Data
        Route::get('tenant-website', [TenantWebsiteController::class, 'index']);

        // Customer Register & Login
        Route::post('customers/register', [CustomerController::class, 'register']);
        Route::post('customers/login', [CustomerController::class, 'login']);

        // Service
        Route::get('consultations/show-public/{id}', [ConsultationController::class, 'show_public']);



    });

    Route::middleware([IdentifyTenant::class, 'auth:sanctum'])->group(function () {
    Route::post('customers/logout', [CustomerController::class, 'logout']);

    // Orders
    Route::post('orders', [OrderController::class, 'store']);
    Route::get('customer-orders', [OrderController::class, 'customer_orders']);

    // Chat Room
    Route::get('customer-chatrooms', [ChatroomController::class, 'customer_chatrooms']);
    Route::get('customer-chatrooms/{id}', [ChatroomController::class, 'customer_chatroom']);
    Route::post('customer-messages', [ChatMessageController::class, 'customer_message']);
});








