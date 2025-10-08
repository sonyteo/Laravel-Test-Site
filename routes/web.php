<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

Route::get('/', [UserController::class, 'home']);
Route::get('/login', [UserController::class, 'home'])->name('login');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'showUsers']);
    Route::post('/admin/users/{id}', [AdminController::class, 'modifyUsers']);
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/admin/users/{id}/send-otp', [AdminController::class, 'sendOtp']);

// Route::get('/verify-otp/{user_id}', [AdminController::class, 'showOtpForm'])->name('verify.otp.form');
// Route::post('/verify-otp/{user_id}', [AdminController::class, 'verifyOtp'])->name('verify.otp');
