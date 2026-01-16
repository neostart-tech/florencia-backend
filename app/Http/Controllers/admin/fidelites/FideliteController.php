<?php

namespace App\Http\Controllers\admin\fidelites;

use App\Http\Controllers\Controller;
use App\Models\Fidelite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\FideliteResource;
use Illuminate\Support\Str;

class FideliteController extends Controller
{
    /**
     * Vérifie si admin ou superadmin
     */
    private function checkAdmin()
    {
        $user = auth()->user();

        if (!$user || $user->role->role === 'user') {
            abort(403, 'Accès refusé');
        }
    }

    /**
     * Liste des codes fidélité
     */
    public function index()
    {
        $this->checkAdmin();

        $fidelites = Fidelite::latest()->get();

        return FideliteResource::collection($fidelites);
    }

    /**
     * Créer un code fidélité (auto généré, sans user)
     */
    public function store(Request $request)
    {
        $this->checkAdmin();

        $validator = Validator::make($request->all(), [
            'pourcentage' => 'required|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Génération automatique du code
        $code = 'FID-' . strtoupper(Str::random(8));

        // S'assurer que le code est unique
        while (Fidelite::where('code', $code)->exists()) {
            $code = 'FID-' . strtoupper(Str::random(8));
        }

        $fidelite = Fidelite::create([
            'code' => $code,
            'pourcentage' => $request->pourcentage,
            'is_active' => true,
            'user_id' => null,
        ]);

        return response()->json([
            'message' => 'Code fidélité créé avec succès',
            'data' => new FideliteResource($fidelite)
        ], 201);
    }

    /**
     * Afficher un code fidélité
     */
    public function show(Fidelite $fidelite)
    {
        $this->checkAdmin();

        return new FideliteResource($fidelite);
    }


    /**
     * Supprimer un code fidélité
     */
    public function destroy(Fidelite $fidelite)
    {
        $this->checkAdmin();

        $fidelite->delete();

        return response()->json([
            'message' => 'Code fidélité supprimé avec succès'
        ]);
    }
}
