<?php

namespace App\Http\Controllers\adresses;

use App\Http\Controllers\Controller;
use App\Models\Adresse;
use Illuminate\Http\Request;
use App\Http\Resources\AdresseResource;
use Illuminate\Support\Facades\Validator;

class AdresseController extends Controller
{
    /**
     * Liste des adresses de l'utilisateur connecté
     */
    public function index()
    {
        \Log::info('Vérification de l\'utilisateur authentifié', ['user' => auth()->user()]);
        $user = auth()->user();

        if (!$user) {
            \Log::error('Utilisateur non authentifié', ['user' => auth()->user()]);
            return response()->json([], 403);
        }

        $adresses = Adresse::where('user_id', $user->id)
            ->latest()
            ->get();

        return AdresseResource::collection($adresses);
    }

    /**
     * Création d'une adresse
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([], 403);
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'code_postal' => 'nullable|string|max:50',
            'tel' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $adresse = Adresse::create([
            'user_id' => $user->id,
            'adresse' => $request->adresse,
            'ville' => $request->ville,
            'code_postal' => $request->code_postal,
            'tel' => $request->tel,
        ]);

        return response()->json([
            'message' => 'Adresse créée avec succès',
            'data' => new AdresseResource($adresse)
        ], 201);
    }

    /**
     * Détails d'une adresse
     */
    public function show(Adresse $adresse)
    {
        $user = auth()->user();

        if ($adresse->user_id !== $user->id) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        return new AdresseResource($adresse);
    }

    /**
     * Mise à jour d'une adresse
     */
    public function update(Request $request, Adresse $adresse)
    {
        $user = auth()->user();

        if ($adresse->user_id !== $user->id) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $validator = Validator::make($request->all(), [
            'adresse' => 'sometimes|string|max:255',
            'ville' => 'sometimes|string|max:255',
            'code_postal' => 'nullable|string|max:50',
            'tel' => 'sometimes|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $adresse->update($validator->validated());

        return response()->json([
            'message' => 'Adresse mise à jour avec succès',
            'data' => new AdresseResource($adresse)
        ]);
    }

    /**
     * Suppression d'une adresse
     */
    public function destroy(Adresse $adresse)
    {
        $user = auth()->user();

        if ($adresse->user_id !== $user->id) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $adresse->delete();

        return response()->json([
            'message' => 'Adresse supprimée avec succès'
        ]);
    }
}
