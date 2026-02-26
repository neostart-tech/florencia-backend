<?php

namespace App\Http\Controllers\admin\commandes;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommandeResource;
use App\Models\Commande;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    private function checkAdmin()
    {
        $user = auth()->user();
        if (!$user || $user->role->role === 'user') {
            abort(403, 'Accès refusé');
        }
    }

    public function index()
    {
        $this->checkAdmin();
        $commandes = Commande::with(['user', 'details', 'paiements'])->latest()->get();
        return CommandeResource::collection($commandes);
    }

    public function show(Commande $commande)
    {
        $this->checkAdmin();
        $commande->load(['user', 'details.article', 'paiements']);
        return new CommandeResource($commande);
    }

    public function update(Request $request, Commande $commande)
    {
        $this->checkAdmin();
        $request->validate([
            'statut' => 'required|string'
        ]);

        $commande->update(['statut' => $request->statut]);

        return response()->json([
            'message' => 'Statut de la commande mis à jour',
            'data' => new CommandeResource($commande->load(['user', 'details', 'paiements']))
        ]);
    }

    public function destroy(Commande $commande)
    {
        $this->checkAdmin();
        $commande->delete();
        return response()->json(['message' => 'Commande supprimée']);
    }
}
