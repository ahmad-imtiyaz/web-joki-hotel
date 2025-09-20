<?php
// File: routes/web.php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CleaningController;
use App\Http\Controllers\UserManagementController;
use App\Http\Middleware\RoleMiddleware; // TAMBAH INI

// Default route
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes
Auth::routes(['register' => false]);

// Protected routes
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rooms - accessible by Super Admin and Kasir
    Route::middleware([RoleMiddleware::class . ':super_admin,kasir'])->group(function () {
        Route::get('/rooms/manage', [RoomController::class, 'manage'])->name('rooms.manage');
        Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
        Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
        Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
        Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
        Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');

        // Booking
        Route::get('/rooms/{room}/book', [BookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
        Route::post('/bookings/{booking}/complete', [BookingController::class, 'complete'])->name('bookings.complete');
    });

    // Cleaning - accessible by Super Admin and Cleaning
    Route::middleware([RoleMiddleware::class . ':super_admin,cleaning'])->group(function () {
        Route::get('/cleaning', [CleaningController::class, 'index'])->name('cleaning.index');
        Route::post('/cleaning/{notification}/accept', [CleaningController::class, 'accept'])->name('cleaning.accept');
        Route::post('/cleaning/{notification}/complete', [CleaningController::class, 'complete'])->name('cleaning.complete');
    });

    // User Management - only Super Admin
    Route::middleware([RoleMiddleware::class . ':super_admin'])->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::post('/users/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('users.reset-password');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    });

    // Profile - accessible by all authenticated users
    Route::get('/profile', [UserManagementController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserManagementController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [UserManagementController::class, 'updatePassword'])->name('profile.password');
});
