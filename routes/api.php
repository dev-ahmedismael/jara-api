<?php

use App\Http\Controllers\Jara\AuthController;
use App\Http\Controllers\Tenant\TenantDataController;
use Illuminate\Support\Facades\Route;


foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        //        Public routes
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        //    Get Website Data
        Route::post('tenant-data', [TenantDataController::class, 'get_data']);
    });
}

