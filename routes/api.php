<?php

use App\Http\Controllers\admin\AuthController as AuthAdmin;
use App\Http\Controllers\users\AuthController as AuthUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Models\User;

// Route::post('/user/verify-email/{id}/{hash}', [AuthUser::class, 'verifyEmailByHash'])
//     ->middleware(['signed'])
//     ->name('verification.verify.api');


Route::prefix('admin')->group(function () {
    Route::post('login', [AuthAdmin::class, 'login']);
});

Route::prefix('user')->group(function () {
    Route::post('login', [AuthUser::class, 'login']);
    Route::post('register', [AuthUser::class, 'register']);
    // Route::post('verify-email', [AuthUser::class, 'verifyEmail']);
    Route::post('forgot-password', [AuthUser::class, 'forgotPassword']);
    Route::post('reset-password', [AuthUser::class, 'resetPassword']);
});
