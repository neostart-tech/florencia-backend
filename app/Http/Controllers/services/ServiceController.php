<?php

namespace App\Http\Controllers\services;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ServiceResource;

class ServiceController extends Controller
{
    /**
     * Vérifie si l'utilisateur est admin ou superadmin
     */
    private function checkAdmin()
    {
        $user = auth()->user();

        if (!$user || $user->role->role === 'user') {
            abort(403, 'Accès refusé');
        }
    }

    /**
     * Liste des services
     */
    public function index()
    {
        $services = Service::latest()->get();
        return ServiceResource::collection($services);
    }

    /**
     * Détails d'un service
     */
    public function show(Service $service)
    {
        return new ServiceResource($service);
    }

    /**
     * Création d'un service (admin seulement)
     */
    public function store(Request $request)
    {
        $this->checkAdmin();

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'duree' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $service = Service::create($validator->validated());

        return response()->json([
            'message' => 'Service créé avec succès',
            'data' => new ServiceResource($service)
        ], 201);
    }

    /**
     * Mise à jour d'un service (admin seulement)
     */
    public function update(Request $request, Service $service)
    {
        $this->checkAdmin();

        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|string|max:255',
            'type' => 'sometimes|string|max:255',
            'duree' => 'sometimes|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $service->update($validator->validated());

        return response()->json([
            'message' => 'Service modifié avec succès',
            'data' => new ServiceResource($service)
        ]);
    }

    /**
     * Suppression d'un service (admin seulement)
     */
    public function destroy(Service $service)
    {
        $this->checkAdmin();

        $service->delete();

        return response()->json([
            'message' => 'Service supprimé avec succès'
        ]);
    }
}
