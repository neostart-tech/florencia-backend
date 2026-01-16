<?php

namespace App\Http\Controllers\admin\codepromos;

use App\Http\Controllers\Controller;
use App\Http\Resources\CodePromoResource;
use App\Models\Code_promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CodePromoController extends Controller
{
    /**
     * Vérifie si l'utilisateur n'est pas un simple user
     */
    private function checkNotUser()
    {
        $user = auth()->user();

        if (!$user || $user->role->role === 'user') {
            abort(403, "Accès refusé");
        }
    }

    /**
     * Liste des codes promos
     */
    public function index()
    {
        $this->checkNotUser();

        return response()->json(Code_promo::latest()->get());
    }

    /**
     * Generer le code promo
     */

    private function generateUniqueCode()
    {
        do {
            $code = 'PROMO-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        } while (Code_promo::where('code', $code)->exists());

        return $code;
    }


    /**
     * Créer un code promo
     */
    public function store(Request $request)
    {
        $this->checkNotUser();

        $validator = Validator::make($request->all(), [
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'pourcentage' => 'required|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $code = $this->generateUniqueCode();

        $codePromo = Code_promo::create([
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'pourcentage' => $request->pourcentage,
            'code' => $code,
        ]);

        return response()->json([
            'message' => 'Code promo généré avec succès',
            'codePromo' => new CodePromoResource($codePromo)
        ], 201);
    }

    /**
     * Afficher un code promo
     */
    public function show(Code_promo $codepromo)
    {
        $this->checkNotUser();

        return new CodePromoResource($codepromo);
    }



    /**
     * Supprimer un code promo
     */
    public function destroy(Code_promo $codepromo)
    {
        $this->checkNotUser();

        $codepromo->delete();

        return response()->json([
            'message' => 'Code promo supprimé avec succès'
        ]);
    }
}
