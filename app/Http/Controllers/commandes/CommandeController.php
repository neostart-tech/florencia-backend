<?php

namespace App\Http\Controllers\commandes;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Commande_detail;
use App\Models\Article;
use App\Models\Code_promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CommandeController extends Controller
{
    /**
     * Liste des commandes de l'utilisateur connecté
     */
    public function index()
    {
        $user = auth()->user();

        return response()->json(
            Commande::with('details.article')
                ->where('user_id', $user->id)
                ->latest()
                ->get()
        );
    }

    /**
     * Liste de toutes les commandes (ADMIN)
     */
    public function allOrders()
    {
        return response()->json(
            Commande::with('details.article', 'user', 'paiements')
                ->latest()
                ->get()
        );
    }

    /**
     * Détail d'une commande
     */
    public function show(Commande $commande)
    {
        $user = auth()->user();

        if ($commande->user_id !== $user->id) {
            abort(403);
        }

        return response()->json(
            $commande->load('details.article', 'paiements')
        );
    }

    /**
     * Créer une commande (USER)
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'articles' => 'required|array|min:1',
            'articles.*.id' => 'required|exists:articles,id',
            'articles.*.quantite' => 'required|integer|min:1',
            'code_promo' => 'nullable|string',
        ]);

        $commande = null;

        DB::transaction(function () use ($request, $user, &$commande) {

            $total = 0;
            $lignes = [];

            // Calcul du total
            foreach ($request->articles as $item) {
                $article = Article::findOrFail($item['id']);

                $prix = $article->prix_promo ?? $article->prix;
                $total += $prix * $item['quantite'];

                $lignes[] = [
                    'article' => $article,
                    'quantite' => $item['quantite'],
                    'prix' => $prix,
                ];
            }

            // Application du code promo
            if ($request->code_promo) {
                $code = Code_promo::where('code', $request->code_promo)
                    ->where('date_debut', '<=', now())
                    ->where('date_fin', '>=', now())
                    ->first();

                if (!$code) {
                    throw new \Exception('Code promo invalide ou expiré');
                }

                if ($user->code_promos()->where('promo_id', $code->id)->exists()) {
                    throw new \Exception('Vous avez déjà utilisé ce code promo');
                }

                $total -= ($total * $code->pourcentage / 100);
                $total = round($total, 2);

                // Enregistrer dans users_code_promos
                $user->code_promos()->attach($code->id);
            }

            // Création de la commande
            $commande = Commande::create([
                'reference' => 'CMD-' . strtoupper(Str::random(8)),
                'prix_total' => round($total, 2),
                'statut' => 'en_cours',
                'user_id' => $user->id,
            ]);

            // Création des détails
            foreach ($lignes as $ligne) {
                Commande_detail::create([
                    'commande_id' => $commande->id,
                    'article_id' => $ligne['article']->id,
                    'quantite' => $ligne['quantite'],
                    'prix_unitaire' => $ligne['prix'],
                ]);
            }
        });

        return response()->json([
            'message' => 'Commande créée avec succès',
            'commande' => $commande->load('details.article'),
        ], 201);
    }

    /**
     * Marquer une commande comme traitée (ADMIN)
     */
    public function traiter(Commande $commande)
    {
        if (!in_array($commande->statut, ['termine'])) {
            return response()->json([
                'message' => 'La commande doit être terminée (payée) avant d\'être traitée'
            ], 400);
        }

        $commande->update(['statut' => 'traite']);

        return response()->json([
            'message' => 'Commande traitée avec succès',
            'commande' => $commande,
        ]);
    }



    /**
     * Suppression définitive (ADMIN)
     */
    // public function destroy(Commande $commande)
    // {
    //     $commande->delete();

    //     return response()->json(['message' => 'Commande supprimée']);
    // }
}