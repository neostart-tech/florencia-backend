<?php

namespace App\Http\Controllers\admin\reservations;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
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
        $reservations = Reservation::with(['user', 'service', 'horaire.jour', 'paiements', 'personnel'])->latest()->get();
        return ReservationResource::collection($reservations);
    }

    public function show(Reservation $reservation)
    {
        $this->checkAdmin();
        $reservation->load(['user', 'service', 'horaire.jour', 'paiements', 'personnel']);
        return new ReservationResource($reservation);
    }

    public function store(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'service_id' => 'required|exists:services,id',
            'horaire_id' => 'required|exists:horaires,id',
            'user_id' => 'required|exists:users,id',
            'personnel_id' => 'required|exists:personnels,id',
        ]);

        // Générer un code unique
        $code = 'RES-' . strtoupper(substr(uniqid(), 7));

        $reservation = Reservation::create([
            'code' => $code,
            'service_id' => $request->service_id,
            'horaire_id' => $request->horaire_id,
            'user_id' => $request->user_id,
            'personnel_id' => $request->personnel_id,
        ]);

        return response()->json([
            'message' => 'Réservation créée avec succès',
            'data' => new ReservationResource($reservation->load(['user', 'service', 'horaire.jour', 'personnel']))
        ], 201);
    }

    public function destroy(Reservation $reservation)
    {
        $this->checkAdmin();
        $reservation->delete();
        return response()->json(['message' => 'Réservation annulée/supprimée']);
    }
}
