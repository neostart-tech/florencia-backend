<?php

namespace App\Http\Controllers\articles;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    

    /**
     *  Liste des articles
     */
    public function index()
    {
        $articles = Article::with([
            'images',
            'stock',
            'sousCategorie',
            'variantes'
        ])->latest()->get();

        return response()->json($articles);
    }

    /**
     *  Détail d'un article
     */
    public function show(Article $article)
    {
        $article->load([
            'images',
            'stock',
            'sousCategorie',
            'variantes'
        ]);

        return response()->json($article);
    }

    /**
     *  Création d'article
     */
    public function store(Request $request)
    {

        $request->validate([
            'nom' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'sous_categorie_id' => 'required|exists:sous_categories,id',
            'images.*' => 'nullable|image|max:4096',
            'variantes' => 'nullable|array',
            'variantes.*' => 'exists:variantes,id',
        ]);

        $article = Article::create($request->only([
            'nom',
            'prix',
            'description',
            'sous_categorie_id'
        ]));

        // Attacher variantes
        if ($request->has('variantes')) {
            $article->variantes()->sync($request->variantes);
        }

        // Upload images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('articles', 'public');

                $article->images()->create([
                    'path' => $path
                ]);
            }
        }

        return response()->json(
            $article->load(['images', 'stock', 'sousCategorie', 'variantes']),
            201
        );
    }

    /**
     *  Modification d'article
     */
    public function update(Request $request, Article $article)
    {

        $request->validate([
            'nom' => 'sometimes|required|string|max:255',
            'prix' => 'sometimes|required|numeric|min:0',
            'description' => 'nullable|string',
            'sous_categorie_id' => 'sometimes|required|exists:sous_categories,id',
            'images.*' => 'nullable|image|max:4096',
            'variantes' => 'nullable|array',
            'variantes.*' => 'exists:variantes,id',
        ]);

        $article->update($request->only([
            'nom',
            'prix',
            'description',
            'sous_categorie_id'
        ]));

        // Sync variantes
        if ($request->has('variantes')) {
            $article->variantes()->sync($request->variantes);
        }

        // Ajouter nouvelles images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('articles', 'public');

                $article->images()->create([
                    'path' => $path
                ]);
            }
        }

        return response()->json(
            $article->load(['images', 'stock', 'sousCategorie', 'variantes'])
        );
    }

    /**
     *  Suppression d'article
     */
    public function destroy(Article $article)
    {

        // Supprimer les images physiques
        foreach ($article->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }

        // Détacher variantes
        $article->variantes()->detach();

        // Supprimer article
        $article->delete();

        return response()->json([
            'message' => 'Article supprimé avec succès'
        ]);
    }
}
