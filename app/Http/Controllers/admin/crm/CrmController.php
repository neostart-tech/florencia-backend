<?php

namespace App\Http\Controllers\admin\crm;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CrmController extends Controller
{
    /**
     * Répertoire client avec statistiques
     */
    public function customers()
    {
        $userRole = Role::where('role', 'user')->first();
        
        $customers = User::where('role_id', $userRole->id)
            ->withCount(['reservations', 'commandes'])
            ->with(['reservations.paiements', 'commandes.paiements'])
            ->get()
            ->map(function ($user) {
                // Calculer le chiffre d'affaires total du client
                $spent = 0;
                foreach ($user->reservations as $res) {
                    $spent += $res->paiements->sum('montant');
                }
                foreach ($user->commandes as $cmd) {
                    $spent += $cmd->paiements->sum('montant');
                }

                return [
                    'id' => $user->id,
                    'nom' => $user->nom,
                    'email' => $user->email,
                    'tel' => $user->tel,
                    'visits' => $user->reservations_count + $user->commandes_count,
                    'spent' => $spent,
                    'category_id' => $this->determineCategory($user->reservations_count + $user->commandes_count, $spent),
                    'created_at' => $user->created_at
                ];
            });

        return response()->json($customers);
    }

    private function determineCategory($visits, $spent)
    {
        if ($spent > 500000 || $visits > 20) return 3; // VIP
        if ($spent > 100000 || $visits > 5) return 2;  // Premium
        if ($visits <= 1) return 4;                   // Nouveau
        return 1;                                     // Standard
    }

    /**
     * Historique d'un client
     */
    public function customerHistory(User $user)
    {
        $reservations = $user->reservations()->with(['service', 'paiements'])->get();
        $commandes = $user->commandes()->with(['paiements'])->get();

        return response()->json([
            'reservations' => $reservations,
            'commandes' => $commandes
        ]);
    }

    /**
     * Alertes nouveaux clients (7 derniers jours)
     */
    public function newCustomerAlerts()
    {
        $userRole = Role::where('role', 'user')->first();
        $newUsers = User::where('role_id', $userRole->id)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->get();

        return response()->json($newUsers);
    }
}
