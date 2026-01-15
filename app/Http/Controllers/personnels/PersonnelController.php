<?php

namespace App\Http\Controllers\personnels;

use App\Http\Controllers\Controller;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PersonnelResource;

class PersonnelController extends Controller
{
    /**
     * Vérifie si l'utilisateur est admin
     */
    private function checkAdmin()
    {
        $user = auth()->user();

        if (!$user || $user->role->role === 'user') {
            abort(403, 'Accès refusé');
        }
    }

    /**
     * Liste du personnel
     */
    public function index()
    {
        $this->checkAdmin();

        $personnels = Personnel::latest()->get();
        return PersonnelResource::collection($personnels);
    }

    /**
     * Ajouter un membre du personnel
     */
    public function store(Request $request)
    {
        $this->checkAdmin();

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'tel' => 'required|string|max:20|unique:personnels',
            'email' => 'required|string|email|max:255|unique:personnels',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $personnel = Personnel::create($validator->validated());

        return response()->json([
            'message' => 'Personnel ajouté avec succès',
            'data' => new PersonnelResource($personnel)
        ], 201);
    }

    /**
     * Détails d'un membre
     */
    public function show(Personnel $personnel)
    {
        $this->checkAdmin();

        return new PersonnelResource($personnel);
    }

    /**
     * Mise à jour
     */
    public function update(Request $request, Personnel $personnel)
    {
        $this->checkAdmin();

        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'tel' => 'sometimes|string|max:20|unique:personnels,tel,' . $personnel->id,
            'email' => 'sometimes|email|unique:personnels,email,' . $personnel->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $personnel->update($validator->validated());

        return response()->json([
            'message' => 'Personnel modifié avec succès',
            'data' => new PersonnelResource($personnel)
        ]);
    }

    /**
     * Suppression
     */
    public function destroy(Personnel $personnel)
    {
        $this->checkAdmin();

        $personnel->delete();

        return response()->json([
            'message' => 'Personnel supprimé avec succès'
        ]);
    }
}
