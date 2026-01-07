<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Work Orders
    Route::prefix('work-orders')->name('work-orders.')->group(function () {
        Route::get('/create', [WorkOrderController::class, 'create'])->name('create');
        Route::post('/', [WorkOrderController::class, 'store'])->name('store');
        Route::get('/{id}', [WorkOrderController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [WorkOrderController::class, 'edit'])->name('edit');
        Route::put('/{id}', [WorkOrderController::class, 'update'])->name('update');
        Route::post('/{id}/upload-evidence', [WorkOrderController::class, 'uploadEvidence'])->name('upload-evidence');
        Route::get('/{id}/photos', [WorkOrderController::class, 'getPhotos'])->name('photos');
        Route::delete('/evidence/{id}', [WorkOrderController::class, 'deleteEvidence'])->name('delete-evidence');
        Route::post('/{id}/submit-daily', [WorkOrderController::class, 'submitDaily'])->name('submit-daily');
        Route::get('/{id}/final-form', [WorkOrderController::class, 'finalForm'])->name('final-form');
        Route::post('/{id}/submit-final', [WorkOrderController::class, 'submitFinal'])->name('submit-final');
    });

    // Track
    Route::prefix('track')->name('track.')->group(function () {
        Route::get('/', [TrackController::class, 'index'])->name('index');
        Route::get('/{id}', [TrackController::class, 'show'])->name('show');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/{id}/preview', [ReportController::class, 'preview'])->name('preview');
        Route::get('/{id}/download', [ReportController::class, 'download'])->name('download');
    });
});

// Fallback for redirect to login
Route::get('/{any}', function () {
    return redirect()->route('login');
})->where('any', '.*');
