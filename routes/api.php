<?php

use App\Http\Controllers\admin\AuthController as AuthAdmin;
use App\Http\Controllers\categories\CategorieController;
use App\Http\Controllers\personnels\PersonnelController;
use App\Http\Controllers\profil\ProfilController;
use App\Http\Controllers\sousCategories\SousCategorieController;
use App\Http\Controllers\adresses\AdresseController;
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
// Categories
Route::get('/categories', [CategorieController::class, 'index']);
Route::get('/categories/{categorie}', [CategorieController::class, 'show']);
// SousCategories
Route::get('/sous-categories', [SousCategorieController::class, 'index']);
Route::get('/sous-categories/{sousCategorie}', [SousCategorieController::class, 'show']);


// Route::prefix('admin')->group(function () {

// })->middleware('auth:sanctum');



/*
     Routes necessitant l'authentification
 */

Route::middleware('auth:sanctum')->group(function () {

    // Variantes
    Route::post('/variantes', [VarianteController::class, 'store']);
    Route::put('/variantes/{variante}', [VarianteController::class, 'update']);
    Route::delete('/variantes/{variante}', [VarianteController::class, 'destroy']);
    // Categories
    Route::post('/categories', [CategorieController::class, 'store']);
    Route::put('/categories/{categorie}', [CategorieController::class, 'update']);
    Route::delete('/categories/{categorie}', [CategorieController::class, 'destroy']);
    // SousCategories
    Route::post('/sous-categories', [SousCategorieController::class, 'store']);
    Route::put('/sous-categories/{sousCategorie}', [SousCategorieController::class, 'update']);
    Route::delete('/sous-categories/{sousCategorie}', [SousCategorieController::class, 'destroy']);
    //Profil
    Route::get('/profil', [ProfilController::class, 'show']);
    Route::put('/profil', [ProfilController::class, 'update']);
    Route::put('/profil/password', [ProfilController::class, 'updatePassword']);
    Route::delete('/profil', [ProfilController::class, 'destroy']);
    //Adresses
    Route::get('/adresses', [AdresseController::class, 'index']);
    Route::get('/adresses/{adresse}', [AdresseController::class, 'show']);
    Route::post('/adresses', [AdresseController::class, 'store']);
    Route::put('/adresses/{adresse}', [AdresseController::class, 'update']);
    Route::delete('/adresses/{adresse}', [AdresseController::class, 'destroy']);
    //Personnels
    Route::get('/personnels', [PersonnelController::class, 'index']);
    Route::post('/personnels', [PersonnelController::class, 'store']);
    Route::get('/personnels/{personnel}', [PersonnelController::class, 'show']);
    Route::put('/personnels/{personnel}', [PersonnelController::class, 'update']);
    Route::delete('/personnels/{personnel}', [PersonnelController::class, 'destroy']);





});
