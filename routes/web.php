<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExcelUploadController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;

// Public Routes - Premium Website
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');

// Filter API endpoints (for dynamic dropdowns with counts)
Route::get('/api/filter/unions', [HomeController::class, 'getUnions']);
Route::get('/api/filter/wards', [HomeController::class, 'getWards']);
Route::get('/api/filter/area-codes', [HomeController::class, 'getAreaCodes']);
Route::get('/api/filter/centers', [HomeController::class, 'getCenters']);
Route::get('/api/filter/count', [HomeController::class, 'getFilterCount']);

// API for offline caching
Route::get('/api/voters/all', [HomeController::class, 'getAllVotersForCache']);
Route::get('/api/voters/sync', [HomeController::class, 'getVotersSyncData']);

// Legacy routes (keep for backwards compatibility)
Route::get('/voters', [VoterController::class, 'index'])->name('voters.index');
Route::get('/voters/search', [VoterController::class, 'search'])->name('voters.search');
Route::get('/api/unions/{upazila}', [VoterController::class, 'getUnions'])->name('api.unions');
Route::get('/api/wards/{upazila}/{union}', [VoterController::class, 'getWards'])->name('api.wards');
Route::get('/api/area-codes/{upazila}/{union}/{ward}', [VoterController::class, 'getAreaCodes'])->name('api.area-codes');
Route::get('/api/centers/{upazila}/{union}/{ward}/{areaCode}', [VoterController::class, 'getCenters'])->name('api.centers');

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
        
        // Banner Management
        Route::get('/banners', [SettingsController::class, 'banners'])->name('banners');
        Route::post('/banners', [SettingsController::class, 'storeBanner'])->name('banners.store');
        Route::post('/banners/{banner}/toggle', [SettingsController::class, 'toggleBanner'])->name('banners.toggle');
        Route::delete('/banners/{banner}', [SettingsController::class, 'deleteBanner'])->name('banners.delete');
        
        // Breaking News Management
        Route::get('/breaking-news', [SettingsController::class, 'breakingNews'])->name('breaking-news');
        Route::post('/breaking-news', [SettingsController::class, 'storeBreakingNews'])->name('breaking-news.store');
        Route::post('/breaking-news/{news}/toggle', [SettingsController::class, 'toggleBreakingNews'])->name('breaking-news.toggle');
        Route::delete('/breaking-news/{news}', [SettingsController::class, 'deleteBreakingNews'])->name('breaking-news.delete');
        
        // User Management
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });
});
