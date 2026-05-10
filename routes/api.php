<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\MeController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\SurveyController;
use Illuminate\Support\Facades\Route;

Route::post('/login', LoginController::class);

Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/logout', LogoutController::class);
    Route::get('/me', MeController::class);

    Route::get('/dashboard', DashboardController::class)
        // Permission: dashboard.view
        // Allowed roles: super_admin, admin, finance, project_manager
        ->middleware('permission:dashboard.view');

    // Permission: items.view
    // Allowed roles: super_admin, admin, finance, project_manager
    Route::middleware('permission:items.view')->group(function (): void {
        Route::get('/items', [ItemController::class, 'index']);
        Route::get('/items/{item}', [ItemController::class, 'show']);
        Route::get('/invoices', [InvoiceController::class, 'index']);
        Route::get('/invoices/{invoice}', [InvoiceController::class, 'show']);
    });

    // Permission: items.manage
    // Allowed roles: super_admin, admin
    Route::middleware('permission:items.manage')->group(function (): void {
        Route::post('/items', [ItemController::class, 'store']);
        Route::put('/items/{item}', [ItemController::class, 'update']);
        Route::delete('/items/{item}', [ItemController::class, 'destroy']);
    });

    // Permission: invoices.create
    // Allowed roles: super_admin, finance
    Route::middleware('permission:invoices.create')->group(function (): void {
        Route::post('/invoices', [InvoiceController::class, 'store']);
    });

    // Permission: projects.view
    // Allowed roles: super_admin, admin, finance, project_manager
    Route::middleware('permission:projects.view')->group(function (): void {
        Route::get('/projects', [ProjectController::class, 'index']);
        Route::get('/projects/{project}', [ProjectController::class, 'show']);
    });

    // Permission: projects.manage
    // Allowed roles: super_admin, project_manager
    Route::middleware('permission:projects.manage')->group(function (): void {
        Route::post('/projects', [ProjectController::class, 'store']);
        Route::put('/projects/{project}', [ProjectController::class, 'update']);
        Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);
    });

    // Permission: surveys.view
    // Allowed roles: super_admin, admin, finance, project_manager
    Route::middleware('permission:surveys.view')->group(function (): void {
        Route::get('/surveys', [SurveyController::class, 'index']);
        Route::get('/surveys/{survey}', [SurveyController::class, 'show']);
    });

    // Permission: surveys.manage
    // Allowed roles: super_admin, project_manager
    Route::middleware('permission:surveys.manage')->group(function (): void {
        Route::post('/surveys', [SurveyController::class, 'store']);
        Route::put('/surveys/{survey}', [SurveyController::class, 'update']);
        Route::delete('/surveys/{survey}', [SurveyController::class, 'destroy']);
    });
});