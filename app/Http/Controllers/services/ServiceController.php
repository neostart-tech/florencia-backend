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
        $services = Service::with(['images', 'reservations'])->latest()->get();

        return ServiceResource::collection($services);
    }


    /**
     * Détails d'un service
     */
    public function show(Service $service)
    {
        $service->load(['images', 'reservations']);

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
            'type' => 'required|in:simple,pack',
            'duree' => 'required|integer|min:1',
            'images' => 'sometimes|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $service = Service::create($validator->validated());

        // Sauvegarde des images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('services', 'public');

                $service->images()->create([
                    'path' => $path
                ]);
            }
        }

        return response()->json([
            'message' => 'Service créé avec succès',
            'data' => new ServiceResource($service->load(['images', 'reservations']))
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
            'type' => 'sometimes|in:simple,pack',
            'duree' => 'sometimes|integer|min:1',
            'images' => 'sometimes|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $service->update($validator->validated());

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('services', 'public');

                $service->images()->create([
                    'path' => $path
                ]);
            }
        }

        return response()->json([
            'message' => 'Service modifié avec succès',
            'data' => new ServiceResource($service->load(['images', 'reservations']))
        ]);
    }

    /**
     * Suppression d'un service (admin seulement)
     */
    public function destroy(Service $service)
    {
        $this->checkAdmin();

        foreach ($service->images as $image) {
            \Storage::disk('public')->delete($image->path);
            $image->delete();
        }

        $service->delete();


        return response()->json([
            'message' => 'Service supprimé avec succès'
        ]);
    }
}
