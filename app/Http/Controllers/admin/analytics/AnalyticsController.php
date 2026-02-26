<?php

namespace App\Http\Controllers\admin\analytics;

use App\Http\Controllers\Controller;
use App\Models\Personnel;
use App\Models\Reservation;
use App\Models\Commande;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Rendements des employés
     */
    public function employeeYields()
    {
        // On vérifie si la colonne personnel_id existe pour éviter l'erreur 500
        // Cette colonne est ajoutée via une migration
        if (!DB::getSchemaBuilder()->hasColumn('reservations', 'personnel_id')) {
            return response()->json([]);
        }

        $employees = Personnel::all()->map(function ($p) {
            $reservations = $p->reservations()->with('paiements')->get();
            $interventions = $reservations->count();
            $yield = 0;
            foreach ($reservations as $res) {
                $yield += $res->paiements->sum('montant');
            }

            return [
                'id' => $p->id,
                'name' => $p->nom,
                'interventions' => $interventions,
                'yield' => $yield,
                'top_service' => $reservations->groupBy('service_id')
                    ->sortByDesc(fn($group) => $group->count())
                    ->first()?->first()?->service?->nom ?? 'N/A'
            ];
        });

        return response()->json($employees);
    }

    /**
     * Rapports périodiques
     */
    public function periodicReport($type)
    {
        $query = Paiement::query();
        
        switch ($type) {
            case 'daily': $query->whereDate('created_at', Carbon::today()); break;
            case 'weekly': $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]); break;
            case 'monthly': $query->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year); break;
            case 'quarterly': $query->whereBetween('created_at', [Carbon::now()->startOfQuarter(), Carbon::now()->endOfQuarter()]); break;
            case 'annual': $query->whereYear('created_at', Carbon::now()->year); break;
        }

        $revenue = $query->sum('montant');
        $count = $query->count();
        
        return response()->json([
            'revenue' => $revenue,
            'count' => $count,
            'avg_basket' => $count > 0 ? $revenue / $count : 0,
            // Autres stats à ajouter
        ]);
    }

    /**
     * Liste toutes les interventions (réservations)
     */
    public function allInterventions()
    {
        $reservations = Reservation::with(['personnel', 'user', 'service', 'paiements'])->latest()->get()->map(function($r) {
            $paiementStatusColumn = null;
            if ($r->paiements->count() > 0) {
                // On vérifie dynamiquement quelle colonne existe (status ou statut)
                $firstPaiement = $r->paiements->first();
                $paiementStatusColumn = isset($firstPaiement->status) ? 'status' : (isset($firstPaiement->statut) ? 'statut' : null);
            }

            $isTermine = false;
            if ($paiementStatusColumn) {
                $isTermine = $r->paiements->where($paiementStatusColumn, 'terminé')->count() > 0;
            }

            return [
                'id' => $r->id,
                'employee' => $r->personnel?->nom ?? 'N/A',
                'client' => $r->user?->nom ?? 'Anonyme',
                'service' => $r->service?->nom ?? 'Service inconnu',
                'date' => $r->created_at,
                'amount' => $r->paiements->sum('montant'),
                'status' => $isTermine ? 'terminé' : 'en cours'
            ];
        });

        return response()->json($reservations);
    }

    /**
     * Interventions par employé
     */
    public function employeeInterventions(Personnel $personnel)
    {
        $reservations = $personnel->reservations()->with(['user', 'service', 'paiements'])->latest()->get()->map(function($r) use ($personnel) {
            $paiementStatusColumn = null;
            if ($r->paiements->count() > 0) {
                $firstPaiement = $r->paiements->first();
                $paiementStatusColumn = isset($firstPaiement->status) ? 'status' : (isset($firstPaiement->statut) ? 'statut' : null);
            }

            $isTermine = false;
            if ($paiementStatusColumn) {
                $isTermine = $r->paiements->where($paiementStatusColumn, 'terminé')->count() > 0;
            }

            return [
                'id' => $r->id,
                'employee' => $personnel->nom,
                'client' => $r->user?->nom ?? 'Anonyme',
                'service' => $r->service?->nom ?? 'Service inconnu',
                'date' => $r->created_at,
                'amount' => $r->paiements->sum('montant'),
                'status' => $isTermine ? 'terminé' : 'en cours'
            ];
        });

        return response()->json($reservations);
    }

    /**
     * Alertes paiements (paiements en attente ou échoués)
     */
    public function paymentAlerts()
    {
        // On vérifie dynamiquement le nom de la colonne pour éviter l'erreur 1054
        $column = DB::getSchemaBuilder()->hasColumn('paiements', 'status') ? 'status' : 'statut';

        $alerts = Paiement::where($column, 'pending')
            ->orWhere($column, 'failed')
            ->with('owner')
            ->get();
            
        return response()->json($alerts);
    }
}
