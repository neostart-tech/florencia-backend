<?php

namespace App\Http\Controllers\Paiements;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use App\Models\Commande;
use App\Models\Reservation;
use App\Services\CashPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class PaiementController extends Controller
{
    /**
     * Initialiser le paiement d'une commande
     */
    public function initierCommande(Request $request, Commande $commande)
    {
        $user = auth()->user();

        if ($commande->user_id !== $user->id) {
            abort(403);
        }

        if ($commande->statut !== 'en_cours') {
            return response()->json(['message' => 'Cette commande ne peut plus être payée'], 400);
        }

        $request->validate([
            'phone'      => 'required|string',
            'gateway_id' => 'required|integer',
        ]);

        $paymentUrl = null;

        DB::transaction(function () use ($request, $commande, &$paymentUrl) {

            $paiement = $commande->paiements()->create([
                'moyen_paiement'        => $request->gateway_id,
                'reference_transaction' => 'TMP-' . uniqid(),
                'statut'                => 'pending',
            ]);

            try {
                $cashpay = app(CashPayService::class);
                $result = $cashpay->createOrder(
                    $commande->prix_total,
                    $request->phone,
                    $request->gateway_id
                );
            } catch (\Throwable $e) {
                logger()->error('Erreur Semoa', ['message' => $e->getMessage()]);
                throw new \Exception("Erreur lors de l'initialisation du paiement");
            }

            $paiement->update(['reference_transaction' => $result['order_reference']]);
            $paymentUrl = $result['bill_url'];
        });

        return response()->json(['success' => true, 'payment_url' => $paymentUrl]);
    }

    /**
     * Initialiser le paiement d'une réservation
     */
    public function initierReservation(Request $request, Reservation $reservation)
    {
        $user = auth()->user();

        if ($reservation->user_id !== $user->id) {
            abort(403);
        }

        if ($reservation->statut !== 'en_cours') {
            return response()->json(['message' => 'Cette réservation ne peut plus être payée'], 400);
        }

        $request->validate([
            'phone'      => 'required|string',
            'gateway_id' => 'required|integer',
        ]);

        $paymentUrl = null;

        DB::transaction(function () use ($request, $reservation, &$paymentUrl) {

            $paiement = $reservation->paiements()->create([
                'moyen_paiement'        => $request->gateway_id,
                'reference_transaction' => 'TMP-' . uniqid(),
                'statut'                => 'pending',
            ]);

            try {
                $cashpay = app(CashPayService::class);
                $result = $cashpay->createOrder(
                    (int) $reservation->prix,
                    $request->phone,
                    $request->gateway_id
                );
            } catch (\Throwable $e) {
                logger()->error('Erreur Semoa', ['message' => $e->getMessage()]);
                throw new \Exception("Erreur lors de l'initialisation du paiement");
            }

            $paiement->update(['reference_transaction' => $result['order_reference']]);
            $paymentUrl = $result['bill_url'];
        });

        return response()->json(['success' => true, 'payment_url' => $paymentUrl]);
    }

    /**
     * Callback Semoa — gère commandes ET réservations
     */
    public function callback(Request $request)
    {
        \Log::info('CALLBACK SEMOA RECU', [
            'body' => $request->getContent(),
            'all'  => $request->all(),
        ]);

        $token = $request->input('token');
        if (!$token) {
            return response()->json(['error' => 'Token manquant'], 400);
        }

        try {
            $payload = JWT::decode(
                $token,
                new Key(config('services.cashpay.apikey'), 'HS256')
            );
        } catch (\Exception $e) {
            \Log::error('JWT invalide', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'JWT invalide'], 400);
        }

        $paiement = Paiement::with('owner')
            ->where('reference_transaction', $payload->order_reference)
            ->first();

        if (!$paiement) {
            return response()->json(['error' => 'Paiement inconnu'], 404);
        }

        $expectedAmount = 0;
        if ($paiement->owner instanceof Commande) {
            $expectedAmount = $paiement->owner->prix_total;
        } elseif ($paiement->owner instanceof Reservation) {
            $expectedAmount = $paiement->owner->prix;
        }

        if ((int) $payload->amount !== (int) $expectedAmount) {
            return response()->json(['error' => 'Montant incohérent'], 403);
        }

        if ($paiement->statut === 'success') {
            return response()->json(['success' => true]);
        }

        DB::transaction(function () use ($payload, $paiement) {

            if ($payload->state !== 'Paid') {
                $paiement->update(['statut' => 'failed']);

                // Annuler la commande ou la réservation
                if ($paiement->owner) {
                    $paiement->owner->update(['statut' => 'annule']);
                }

                return;
            }

            // Paiement réussi
            $paiement->update(['statut' => 'success']);

            if ($paiement->owner) {
                $paiement->owner->update(['statut' => 'termine']);
            }
        });

        return response()->json(['success' => true]);
    }

    /**
     * Statut d'un paiement (USER)
     */
    public function show($id)
    {
        $paiement = Paiement::with('owner')->findOrFail($id);

        $userId = $paiement->owner?->user_id;

        if ($userId !== auth()->id()) {
            abort(403);
        }

        return response()->json($paiement);
    }

    /**
     * Liste de tous les paiements (ADMIN)
     */
    public function index()
    {
        $paiements = Paiement::with('owner.user')
            ->latest()
            ->get();

        return response()->json($paiements);
    }

    /**
     * Paiements de l'utilisateur connecté
     */
    public function userPayments()
    {
        $paiements = Paiement::with('owner')
            ->whereHasMorph('owner', [Commande::class, Reservation::class], function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->latest()
            ->get();

        return response()->json($paiements);
    }
}