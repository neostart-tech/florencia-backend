<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Fidelite;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    private function isUser()
    {
        return auth()->user()->role->role === 'user';
    }

    // Liste
    public function index()
    {
        $user = auth()->user();

        if ($this->isUser()) {
            return Reservation::with(['service', 'horaire'])
                ->where('user_id', $user->id)
                ->latest()->get();
        }

        return Reservation::with(['service', 'horaire', 'user'])->latest()->get();
    }

    // Voir
    public function show(Reservation $reservation)
    {
        $user = auth()->user();

        if ($this->isUser() && $reservation->user_id !== $user->id) {
            abort(403);
        }

        return $reservation->load(['service', 'horaire', 'paiement']);
    }

    // Créer réservation
    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'service_id' => 'required|exists:services,id',
            'horaire_id' => 'required|exists:horaires,id',
            'code_fidelite' => 'nullable|string'
        ]);

        $service = Service::findOrFail($request->service_id);

        // Prix officiel depuis la base
        $prix = $service->prix;

        // Fidélité
        if ($request->code_fidelite) {
            $fidelite = Fidelite::where('code', $request->code_fidelite)
                ->whereNull('user_id')
                ->first();

            if (!$fidelite) {
                return response()->json(['message' => 'Code fidélité invalide'], 403);
            }

            $prix -= ($prix * $fidelite->pourcentage / 100);

            $fidelite->update(['user_id' => $user->id]);
        }

        $reservation = Reservation::create([
            'code' => 'RES-' . strtoupper(Str::random(8)),
            'service_id' => $request->service_id,
            'horaire_id' => $request->horaire_id,
            'user_id' => $user->id,
            'prix' => $prix
        ]);

        return response()->json([
            'message' => 'Réservation créée',
            'reservation' => $reservation
        ], 201);
    }

    // Suppression (ADMIN)
    public function destroy(Reservation $reservation)
    {
        if ($this->isUser())
            abort(403);

        $reservation->delete();

        return response()->json(['message' => 'Réservation supprimée']);
    }
}
