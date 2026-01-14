<?php

namespace App\Http\Controllers\variantes;

use App\Http\Controllers\Controller;
use App\Models\Variante;
use Illuminate\Http\Request;
use App\Http\Resources\VarianteResource;
use Illuminate\Support\Facades\Validator;

class VarianteController extends Controller
{
    /**
     * Liste des variantes
     */
    public function index()
    {
        $variantes = Variante::latest()->get();
        return VarianteResource::collection($variantes);
    }

    /**
     * Création d'une variante (admin seulement)
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // Vérifier rôle
        if ($user->role->role === 'user') {
            return response()->json([
                'message' => 'Accès refusé'
            ], 403);
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'libelle' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $variante = Variante::create($validator->validated());

        return response()->json([
            'message' => 'Variante créée avec succès',
            'data' => new VarianteResource($variante)
        ], 201);
    }

    /**
     * Détails d'une variante
     */
    public function show(Variante $variante)
    {
        return new VarianteResource($variante);
    }

    /**
     * Mise à jour (admin seulement)
     */
    public function update(Request $request, Variante $variante)
    {
        $user = auth()->user();

        if ($user->role->role === 'user') {
            return response()->json([
                'message' => 'Accès refusé'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'libelle' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $variante->update($validator->validated());

        return response()->json([
            'message' => 'Variante mise à jour avec succès',
            'data' => new VarianteResource($variante)
        ]);
    }

    /**
     * Suppression (admin seulement)
     */
    public function destroy(Variante $variante)
    {
        $user = auth()->user();

        if ($user->role->role === 'user') {
            return response()->json([
                'message' => 'Accès refusé'
            ], 403);
        }

        $variante->delete();

        return response()->json([
            'message' => 'Variante supprimée avec succès'
        ]);
    }
}
