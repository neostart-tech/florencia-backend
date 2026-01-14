<?php

use App\Http\Controllers\admin\AuthController as AuthAdmin;
use App\Http\Controllers\users\AuthController as AuthUser;
use App\Http\Controllers\variantes\VarianteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Models\User;

// Route::post('/user/verify-email/{id}/{hash}', [AuthUser::class, 'verifyEmailByHash'])
//     ->middleware(['signed'])
//     ->name('verification.verify.api');


/*
     Routes ne necessitant pas l'authentification
 */

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

// Variantes
Route::get('/variantes', [VarianteController::class, 'index']);
Route::get('/variantes/{variante}', [VarianteController::class, 'show']);


// Route::prefix('admin')->group(function () {

// })->middleware('auth:sanctum');

// Route::prefix('user')->group(function () {

// })->middleware('auth:sanctum');


/*
     Routes necessitant l'authentification
 */

Route::middleware('auth:sanctum')->group(function () {

    // Variantes
    Route::post('/variantes', [VarianteController::class, 'store']);
    Route::put('/variantes/{variante}', [VarianteController::class, 'update']);
    Route::delete('/variantes/{variante}', [VarianteController::class, 'destroy']);


});
