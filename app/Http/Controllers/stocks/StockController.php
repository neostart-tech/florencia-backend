<?php

namespace App\Http\Controllers\stocks;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Stock_mouvement;
use App\Models\Article;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Liste des stocks
     */
    public function index()
    {
        $stocks = Stock::with('article')->get();
        return response()->json($stocks);
    }

    /**
     * Détail stock d’un article
     */
    public function show($articleId)
    {
        $stock = Stock::with('article')->where('article_id', $articleId)->firstOrFail();
        return response()->json($stock);
    }

    /**
     * Ajouter un mouvement de stock (entrée ou sortie)
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->role->slug === 'user') {
            return response()->json(['message' => 'Accès interdit'], 403);
        }

        $data = $request->validate([
            'article_id' => 'required|exists:articles,id',
            'type' => 'required|in:entree,sortie',
            'quantite' => 'required|integer|min:1',
            'commentaire' => 'nullable|string',
        ]);

        $article = Article::findOrFail($data['article_id']);

        // Récupérer ou créer le stock
        $stock = Stock::firstOrCreate(
            ['article_id' => $article->id],
            ['quantite' => 0]
        );

        // Si sortie, vérifier stock
        if ($data['type'] === 'sortie' && $stock->quantite < $data['quantite']) {
            return response()->json([
                'message' => 'Stock insuffisant'
            ], 422);
        }

        // Créer mouvement
        $mouvement = Stock_mouvement::create($data);

        // Mettre à jour le stock
        if ($data['type'] === 'entree') {
            $stock->increment('quantite', $data['quantite']);
        } else {
            $stock->decrement('quantite', $data['quantite']);
        }

        return response()->json([
            'message' => 'Mouvement enregistré',
            'stock' => $stock,
            'mouvement' => $mouvement
        ]);
    }

    // /**
    //  * Modifier un mouvement (ADMIN SEULEMENT)
    //  */
    // public function update(Request $request, $id)
    // {
    //     $user = auth()->user();

    //     if ($user->role->slug === 'user') {
    //         return response()->json(['message' => 'Accès interdit'], 403);
    //     }

    //     $mouvement = Stock_mouvement::findOrFail($id);

    //     return response()->json([
    //         'message' => 'Modification interdite pour préserver l’historique'
    //     ], 403);
    // }

    // /**
    //  * Supprimer un mouvement (ADMIN SEULEMENT)
    //  */
    // public function destroy($id)
    // {
    //     $user = auth()->user();

    //     if ($user->role->slug === 'user') {
    //         return response()->json(['message' => 'Accès interdit'], 403);
    //     }

    //     $mouvement = Stock_mouvement::findOrFail($id);

    //     return response()->json([
    //         'message' => 'Suppression interdite pour préserver l’historique'
    //     ], 403);
    // }

    /**
     * Historique des mouvements d’un article
     */
    public function mouvements($articleId)
    {
        $mouvements = Stock_mouvement::where('article_id', $articleId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($mouvements);
    }
}
