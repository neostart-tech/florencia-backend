<?php

namespace App\Http\Controllers\sousCategories;

use App\Http\Controllers\Controller;
use App\Models\Sous_categorie;
use Illuminate\Http\Request;
use App\Http\Resources\SousCategorieResource;
use Illuminate\Support\Facades\Validator;

class SousCategorieController extends Controller
{
    /**
     * Liste
     */
    public function index()
    {
        $items = Sous_categorie::with('categorie')->latest()->get();
        return SousCategorieResource::collection($items);
    }

    /**
     * Création (admin)
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->role->role === 'user') {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $validator = Validator::make($request->all(), [
            'libelle' => 'required|string|max:255',
            'categorie_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $sousCategorie = Sous_categorie::create($validator->validated());

        return response()->json([
            'message' => 'Sous-catégorie créée avec succès',
            'data' => new SousCategorieResource($sousCategorie)
        ], 201);
    }

    /**
     * Détail
     */
    public function show(Sous_categorie $sousCategorie)
    {
        $sousCategorie->load('categorie');
        return new SousCategorieResource($sousCategorie);
    }

    /**
     * Update (admin)
     */
    public function update(Request $request, Sous_categorie $sousCategorie)
    {
        $user = auth()->user();

        if ($user->role->role === 'user') {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $validator = Validator::make($request->all(), [
            'libelle' => 'sometimes|string|max:255',
            'categorie_id' => 'sometimes|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $sousCategorie->update($validator->validated());

        return response()->json([
            'message' => 'Sous-catégorie mise à jour',
            'data' => new SousCategorieResource($sousCategorie)
        ]);
    }

    /**
     * Suppression (admin)
     */
    public function destroy(Sous_categorie $sousCategorie)
    {
        $user = auth()->user();

        if ($user->role->role === 'user') {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $sousCategorie->delete();

        return response()->json([
            'message' => 'Sous-catégorie supprimée'
        ]);
    }
}
