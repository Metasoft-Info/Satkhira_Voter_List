<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoterController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExcelUploadController;

// Public Routes
Route::get('/', [VoterController::class, 'index'])->name('voters.index');
Route::get('/voters/search', [VoterController::class, 'search'])->name('voters.search');
Route::get('/api/unions/{upazila}', [VoterController::class, 'getUnions'])->name('api.unions');
Route::get('/api/area-codes/{union}', [VoterController::class, 'getAreaCodes'])->name('api.area-codes');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    });

    // Authenticated routes
    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/voters', [DashboardController::class, 'voters'])->name('voters');
        Route::get('/dropdowns', [DashboardController::class, 'dropdowns'])->name('dropdowns');
        
        // Excel Upload
        Route::get('/upload', [ExcelUploadController::class, 'index'])->name('upload');
        Route::post('/upload', [ExcelUploadController::class, 'upload'])->name('upload.submit');
        Route::post('/reset-voters', [ExcelUploadController::class, 'resetVoters'])->name('reset.voters');
    });
});

