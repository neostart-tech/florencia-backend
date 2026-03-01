<?php

namespace App\Http\Controllers\reservations;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Fidelite;
use App\Models\Service;
use App\Models\User;
use App\Models\Horaire;
use App\Notifications\ReservationTermineeNotification;
use App\Notifications\NouvelleReservationNotification;
use App\Services\CashPayService;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    /**
     * Liste des réservations de l'utilisateur connecté
     */
    public function index()
    {
        $user = auth()->user();

        return response()->json(
            Reservation::with(['service', 'horaire', 'paiement'])
                ->where('user_id', $user->id)
                ->latest()
                ->get()
        );
    }

    /**
     * Liste de toutes les réservations (ADMIN)
     */
    public function allReservations()
    {
        return response()->json(
            Reservation::with(['service', 'horaire', 'paiement', 'user'])
                ->latest()
                ->get()
        );
    }

    /**
     * Détail d'une réservation
     */
    public function show(Reservation $reservation)
    {
        $user = auth()->user();

        if ($reservation->user_id !== $user->id) {
            abort(403);
        }

        return response()->json(
            $reservation->load(['service', 'horaire', 'paiement'])
        );
    }

    /**
     * Créer une réservation (USER)
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'horaire_id'    => 'required|exists:horaires,id',
            'code_fidelite' => 'nullable|string',
        ]);

        $paymentUrl = null;
        $reservation = null;

        DB::transaction(function () use ($request, $user, &$paymentUrl, &$reservation) {

            $horaire = Horaire::findOrFail($request->horaire_id);
            $service = Service::findOrFail($horaire->service_id);
            $prix = $service->prix;

            // Application du code fidélité
            if ($request->code_fidelite) {
                $fidelite = Fidelite::where('code', $request->code_fidelite)
                    ->first();

                if (!$fidelite) {
                    throw new \Exception('Code fidélité invalide');
                }

                // Vérifier que le code n'a pas déjà été utilisé
                if (!is_null($fidelite->user_id)) {
                    throw new \Exception('Ce code fidélité a déjà été utilisé');
                }

                $prix -= ($prix * $fidelite->pourcentage / 100);
                $prix = round($prix, 2);

                // Marquer le code comme utilisé
                $fidelite->update(['user_id' => $user->id]);
            }

            // Création de la réservation
            $reservation = Reservation::create([
                'code'       => 'RES-' . strtoupper(Str::random(8)),
                'service_id' => $horaire->service_id,
                'horaire_id' => $request->horaire_id,
                'user_id'    => $user->id,
                'prix'       => $prix,
                'statut'     => 'en_cours',
            ]);

            // Création du paiement (PENDING)
            // $paiement = Paiement::create([
            //     'reservation_id'        => $reservation->id,
            //     'moyen_paiement'        => $request->gateway_id,
            //     'reference_transaction' => 'TMP-' . uniqid(),
            //     'montant'               => $prix,
            //     'statut'                => 'pending',
            // ]);

            // // Appel Semoa CashPay
            // try {
            //     $cashpay = app(CashPayService::class);

            //     $result = $cashpay->createOrder(
            //         $prix,
            //         $request->phone,
            //         $request->gateway_id
            //     );
            // } catch (\Throwable $e) {
            //     logger()->error('Erreur Semoa', ['message' => $e->getMessage()]);
            //     throw new \Exception("Erreur lors de l'initialisation du paiement");
            // }

            // // Mettre à jour la référence Semoa
            // $paiement->update([
            //     'reference_transaction' => $result['order_reference']
            // ]);

            // $paymentUrl = $result['bill_url'];
        });

        return response()->json([
            'message' => 'Réservation créée avec succès',
            'reservation' => $reservation,
        ], 201);
    }

    /**
     * Marquer une réservation comme traitée (ADMIN)
     */
    public function traiter(Reservation $reservation)
    {
        if ($reservation->statut !== 'termine') {
            return response()->json([
                'message' => 'La réservation doit être terminée (payée) avant d\'être traitée'
            ], 400);
        }

        $reservation->update(['statut' => 'traite']);

        return response()->json([
            'message'     => 'Réservation traitée avec succès',
            'reservation' => $reservation,
        ]);
    }

    

    /**
     * Suppression définitive (ADMIN)
     */
    // public function destroy(Reservation $reservation)
    // {
    //     $reservation->delete();
    //     return response()->json(['message' => 'Réservation supprimée']);
    // }
}