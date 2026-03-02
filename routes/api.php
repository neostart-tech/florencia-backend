<?php

use App\Http\Controllers\admin\AuthController as AuthAdmin;
use App\Http\Controllers\admin\codepromos\CodePromoController;
use App\Http\Controllers\admin\fidelites\FideliteController;
use App\Http\Controllers\admin\utilisateurs\UtilisateurController;
use App\Http\Controllers\articles\ArticleController;
use App\Http\Controllers\calendriers\CalendrierController;
use App\Http\Controllers\categories\CategorieController;
use App\Http\Controllers\commandes\CommandeController;
use App\Http\Controllers\gateways\GatewayController;
use App\Http\Controllers\horaires\HoraireController;
use App\Http\Controllers\Paiements\PaiementController;
use App\Http\Controllers\personnels\PersonnelController;
use App\Http\Controllers\profil\ProfilController;
use App\Http\Controllers\reservations\ReservationController;
use App\Http\Controllers\services\ServiceController;
use App\Http\Controllers\sousCategories\SousCategorieController;
use App\Http\Controllers\adresses\AdresseController;
use App\Http\Controllers\stocks\StockController;
use App\Http\Controllers\users\AuthController as AuthUser;
use App\Http\Controllers\variantes\VarianteController;
use App\Http\Middleware\IsAdminOrSuperAdmin;
use App\Http\Middleware\IsSuperAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

/*
 * Routes ne nécessitant pas l'authentification
 */

Route::prefix('admin')->group(function () {
    Route::post('login', [AuthAdmin::class, 'login']);
});

Route::prefix('user')->group(function () {
    Route::post('login', [AuthUser::class, 'login']);
    Route::post('register', [AuthUser::class, 'register']);
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

// Services
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{service}', [ServiceController::class, 'show']);

// Articles
Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{article}', [ArticleController::class, 'show']);

// Stocks
Route::get('/stocks', [StockController::class, 'index']);
Route::get('/stocks/{article}', [StockController::class, 'show']);

// Calendriers
Route::get('/calendriers', [CalendrierController::class, 'index']);
Route::get('/calendriers/{calendrier}', [CalendrierController::class, 'show']);

// Horaires
Route::get('/horaires', [HoraireController::class, 'index']);
Route::get('/horaires/{horaire}', [HoraireController::class, 'show']);

// Gateways
Route::get('/gateways', [GatewayController::class, 'index']);

// Callback Semoa (sans auth)
Route::post('/paiements/callback', [PaiementController::class, 'callback'])
    ->name('semoa.callback');


/*
 * Routes nécessitant l'authentification
 */

Route::middleware('auth:sanctum')->group(function () {

    // Variantes
    Route::prefix('/variantes')->group(function () {
        Route::post('/', [VarianteController::class, 'store']);
        Route::put('/{variante}', [VarianteController::class, 'update']);
        Route::delete('/{variante}', [VarianteController::class, 'destroy']);
    })->middleware(IsAdminOrSuperAdmin::class);

    // Categories
    Route::prefix('/categories')->group(function () {
        Route::post('/', [CategorieController::class, 'store']);
        Route::put('/{categorie}', [CategorieController::class, 'update']);
        Route::delete('/{categorie}', [CategorieController::class, 'destroy']);
    })->middleware(IsAdminOrSuperAdmin::class);

    // SousCategories
    Route::prefix('/sous-categories')->group(function () {
        Route::post('/', [SousCategorieController::class, 'store']);
        Route::put('/{sousCategorie}', [SousCategorieController::class, 'update']);
        Route::delete('/{sousCategorie}', [SousCategorieController::class, 'destroy']);
    })->middleware(IsAdminOrSuperAdmin::class);

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

    // Services
    Route::prefix('/services')->group(function () {
        Route::post('/', [ServiceController::class, 'store']);
        Route::put('/{service}', [ServiceController::class, 'update']);
        Route::delete('/{service}', [ServiceController::class, 'destroy']);
    })->middleware(IsAdminOrSuperAdmin::class);

    // Articles
    Route::prefix('/articles')->group(function () {
        Route::post('/', [ArticleController::class, 'store']);
        Route::put('/{article}', [ArticleController::class, 'update']);
        Route::delete('/{article}', [ArticleController::class, 'destroy']);
    })->middleware(IsAdminOrSuperAdmin::class);

    // Stocks
    Route::prefix('/stocks')->group(function () {
        Route::get('/{article}/mouvements', [StockController::class, 'mouvements']);
        Route::post('/mouvement', [StockController::class, 'store']);
    })->middleware(IsAdminOrSuperAdmin::class);

    // Calendriers
    Route::prefix('/calendriers')->group(function () {
        Route::post('/', [CalendrierController::class, 'store']);
        Route::put('/{calendrier}', [CalendrierController::class, 'update']);
        Route::delete('/{calendrier}', [CalendrierController::class, 'destroy']);
    })->middleware(IsAdminOrSuperAdmin::class);

    // Horaires
    Route::prefix('/horaires')->group(function () {
        Route::post('/', [HoraireController::class, 'store']);
        Route::put('/{horaire}', [HoraireController::class, 'update']);
        Route::delete('/{horaire}', [HoraireController::class, 'destroy']);
    })->middleware(IsAdminOrSuperAdmin::class);

    // Personnels
    Route::prefix('/personnels')->group(function () {
        Route::get('/', [PersonnelController::class, 'index']);
        Route::post('/', [PersonnelController::class, 'store']);
        Route::get('/{id}', [PersonnelController::class, 'show']);
        Route::put('/{id}', [PersonnelController::class, 'update']);
        Route::delete('/{id}', [PersonnelController::class, 'destroy']);
    })->middleware(IsAdminOrSuperAdmin::class);

    // CAISSIERS
    Route::prefix('/caissiers')->group(function () {
        Route::get('/', [PersonnelController::class, 'index']);
        Route::post('/', [PersonnelController::class, 'store']);
        Route::get('/{id}', [PersonnelController::class, 'show']);
        Route::put('/{id}', [PersonnelController::class, 'update']);
        Route::delete('/{id}', [PersonnelController::class, 'destroy']);
    })->middleware(IsAdminOrSuperAdmin::class);

    // Gestion des utilisateurs et administrateurs
    Route::prefix('/admins')->group(function () {
        Route::get('/all-users', [UtilisateurController::class, 'allUsers'])->middleware(IsAdminOrSuperAdmin::class);

        Route::middleware(IsSuperAdmin::class)->group(function () {
            Route::get('/', [UtilisateurController::class, 'index']);
            Route::post('/', [UtilisateurController::class, 'store']);
            Route::get('/{user}', [UtilisateurController::class, 'show']);
            Route::put('/{user}', [UtilisateurController::class, 'update']);
            Route::delete('/{user}', [UtilisateurController::class, 'destroy']);
            Route::put('/{user}/make-admin', [UtilisateurController::class, 'makeAdmin']);
            Route::put('/{user}/make-user', [UtilisateurController::class, 'makeUser']);
        });
    });

    // Commandes (USER)
    Route::prefix('/commandes')->group(function () {
        Route::get('/', [CommandeController::class, 'index']);
        Route::post('/', [CommandeController::class, 'store']);
        Route::get('/{commande}', [CommandeController::class, 'show']);
        Route::patch('/{commande}/annuler', [CommandeController::class, 'annuler']);
    });

    // Commandes (ADMIN)
    Route::prefix('/commandes')->group(function () {
        Route::get('/all', [CommandeController::class, 'allOrders']);
        Route::patch('/{commande}/traiter', [CommandeController::class, 'traiter']);
    })->middleware(IsAdminOrSuperAdmin::class);

    // Réservations (USER)
    Route::prefix('/reservations')->group(function () {
        Route::get('/', [ReservationController::class, 'index']);
        Route::post('/', [ReservationController::class, 'store']);
        Route::get('/{reservation}', [ReservationController::class, 'show']);
        Route::patch('/{reservation}/annuler', [ReservationController::class, 'annuler']);
    });

    // Réservations (ADMIN)
    Route::prefix('/reservations')->group(function () {
        Route::get('/all', [ReservationController::class, 'allReservations']);
        Route::patch('/{reservation}/traiter', [ReservationController::class, 'traiter']);
    })->middleware(IsAdminOrSuperAdmin::class);

    // Paiements — initiation
    Route::post('/commandes/{commande}/paiement', [PaiementController::class, 'initierCommande']);
    Route::post('/reservations/{reservation}/paiement', [PaiementController::class, 'initierReservation']);

    // Paiements — consultation (USER)
    Route::prefix('/paiements')->group(function () {
        Route::get('/mes-paiements', [PaiementController::class, 'userPayments']);
        Route::get('/{id}', [PaiementController::class, 'show']);
    });

    // Paiements — liste complète (ADMIN)
    Route::get('/paiements', [PaiementController::class, 'index'])
        ->middleware(IsAdminOrSuperAdmin::class);

    // Code promos (ADMIN)
    Route::prefix('/codepromos')->group(function () {
        Route::get('/', [CodePromoController::class, 'index']);
        Route::get('/{codepromo}', [CodePromoController::class, 'show']);
        Route::post('/', [CodePromoController::class, 'store']);
        Route::post('/{codepromo}/assigner', [CodePromoController::class, 'assignerUtilisateurs']);
        Route::delete('/{codepromo}/retirer/{user}', [CodePromoController::class, 'retirerUtilisateur']);
        Route::patch('/{codepromo}/toggle', [CodePromoController::class, 'toggleActif']);
        Route::delete('/{codepromo}', [CodePromoController::class, 'destroy']);
    });

    // Code promos (USER)
    Route::get('/mes-codepromos', [CodePromoController::class, 'mesCodesPromo']);

    // Fidélités (ADMIN)
    Route::prefix('/fidelites')->group(function () {
        Route::get('/', [FideliteController::class, 'index']);
        Route::get('/{fidelite}', [FideliteController::class, 'show']);
        Route::post('/', [FideliteController::class, 'store']);
        Route::delete('/{fidelite}', [FideliteController::class, 'destroy']);
    })->middleware(IsAdminOrSuperAdmin::class);

    // Notifications (USER + ADMIN)
    Route::prefix('/notifications')->group(function () {
        Route::get('/', function (Request $request) {
            return $request->user()->notifications;
        });

        Route::post('/read-all', function (Request $r) {
            $r->user()->unreadNotifications->markAsRead();
            return response()->noContent();
        });

        Route::post('/{id}/read', function ($id, Request $request) {
            $request->user()->notifications()->findOrFail($id)->markAsRead();
            return response()->noContent();
        });

        Route::delete('/{id}', function ($id, Request $request) {
            $request->user()->notifications()->findOrFail($id)->delete();
            return response()->noContent();
        });
    });

});