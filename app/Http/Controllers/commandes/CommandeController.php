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
    private function isUser()
    {
        return auth()->user()->role->role === 'user';
    }

    // Liste
    public function index()
    {
        $user = auth()->user();

        if ($this->isUser()) {
            return Commande::with('details.article')
                ->where('user_id', $user->id)
                ->latest()->get();
        }

        return Commande::with('details.article', 'user')->latest()->get();
    }

    // Voir une commande
    public function show(Commande $commande)
    {
        $user = auth()->user();

        if ($this->isUser() && $commande->user_id !== $user->id) {
            abort(403, 'Accès refusé');
        }

        return $commande->load('details.article', 'paiements');
    }

    // Créer commande (USER)
    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'articles' => 'required|array|min:1',
            'articles.*.id' => 'required|exists:articles,id',
            'articles.*.quantite' => 'required|integer|min:1',
            'code_promo' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {

            $total = 0;

            foreach ($request->articles as $item) {
                $article = Article::findOrFail($item['id']);
                $total += $article->prix * $item['quantite'];
            }

            // Code promo
            if ($request->code_promo) {
                $code = Code_promo::where('code', $request->code_promo)->first();

                if (!$code || !$user->codePromos->contains($code->id)) {
                    return response()->json(['message' => 'Code promo invalide'], 403);
                }

                $total -= ($total * $code->pourcentage / 100);
            }

            $commande = Commande::create([
                'reference' => 'CMD-' . strtoupper(Str::random(8)),
                'prix_total' => $total,
                'statut' => 'en_cours',
                'user_id' => $user->id
            ]);

            foreach ($request->articles as $item) {
                $article = Article::findOrFail($item['id']);

                Commande_detail::create([
                    'commande_id' => $commande->id,
                    'article_id' => $article->id,
                    'quantite' => $item['quantite'],
                    'prix_unitaire' => $article->prix
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Commande créée',
                'commande' => $commande->load('details.article')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // Suppression (ADMIN)
    public function destroy(Commande $commande)
    {
        if ($this->isUser())
            abort(403);

        $commande->delete();

        return response()->json(['message' => 'Commande supprimée']);
    }
}
