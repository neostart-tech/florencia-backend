<?php

use App\Http\Controllers\admin\AuthController as AuthAdmin;
use App\Http\Controllers\admin\codepromos\CodePromoController;
use App\Http\Controllers\admin\fidelites\FideliteController;
use App\Http\Controllers\admin\utilisateurs\UtilisateurController;
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
    Route::prefix('/variantes')->group(function () {
        Route::post('/', [VarianteController::class, 'store']);
        Route::put('/{variante}', [VarianteController::class, 'update']);
        Route::delete('/{variante}', [VarianteController::class, 'destroy']);
    });

    // Categories
    Route::prefix('/categories')->group(function () {
        Route::post('/', [CategorieController::class, 'store']);
        Route::put('/{categorie}', [CategorieController::class, 'update']);
        Route::delete('/{categorie}', [CategorieController::class, 'destroy']);
    });

    // SousCategories
    Route::prefix('/sous-categories')->group(function () {
        Route::post('/', [SousCategorieController::class, 'store']);
        Route::put('/{sousCategorie}', [SousCategorieController::class, 'update']);
        Route::delete('/{sousCategorie}', [SousCategorieController::class, 'destroy']);
    });

    // Profil
    Route::prefix('/profil')->group(function () {
        Route::get('/', [ProfilController::class, 'show']);
        Route::put('/', [ProfilController::class, 'update']);
        Route::put('/password', [ProfilController::class, 'updatePassword']);
        Route::delete('/', [ProfilController::class, 'destroy']);
    });

    // Adresses
    Route::prefix('/adresses')->group(function () {
        Route::get('/', [AdresseController::class, 'index']);
        Route::get('/{adresse}', [AdresseController::class, 'show']);
        Route::post('/', [AdresseController::class, 'store']);
        Route::put('/{adresse}', [AdresseController::class, 'update']);
        Route::delete('/{adresse}', [AdresseController::class, 'destroy']);
    });

    // Personnels
    Route::prefix('/personnels')->group(function () {
        Route::get('/', [PersonnelController::class, 'index']);
        Route::post('/', [PersonnelController::class, 'store']);
        Route::get('/{personnel}', [PersonnelController::class, 'show']);
        Route::put('/{personnel}', [PersonnelController::class, 'update']);
        Route::delete('/{personnel}', [PersonnelController::class, 'destroy']);
    });

    // Gestion des administrateurs (superadmin seulement)
    Route::prefix('/admins')->group(function () {
        Route::get('/', [UtilisateurController::class, 'index']);
        Route::post('/', [UtilisateurController::class, 'store']);
        Route::get('/{user}', [UtilisateurController::class, 'show']);
        Route::put('/{user}', [UtilisateurController::class, 'update']);
        Route::delete('/{user}', [UtilisateurController::class, 'destroy']);
    });

    // Transformer un user en admin
    Route::put('/users/{user}/make-admin', [UtilisateurController::class, 'makeAdmin']);

    // Rétrograder un admin en user
    Route::put('/users/{user}/make-user', [UtilisateurController::class, 'makeUser']);

    // Gestion des CodePromos (superadmins et admins)
    Route::prefix('/codepromos')->group(function () {
        Route::get('/', [CodePromoController::class, 'index']);
        Route::get('/{codepromo}', [CodePromoController::class, 'show']);
        Route::post('/', [CodePromoController::class, 'store']);
        Route::delete('/{codepromo}', [CodePromoController::class, 'destroy']);
    });

    // Gestion des Fidelites (superadmins et admins)
    Route::prefix('/fidelites')->group(function () {
        Route::get('/', [FideliteController::class, 'index']);
        Route::get('/{fidelite}', [FideliteController::class, 'show']);
        Route::post('/', [FideliteController::class, 'store']);
        Route::delete('/{fidelite}', [FideliteController::class, 'destroy']);
    });





});
