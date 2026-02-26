<?php

namespace App\Http\Controllers\horaires;

use App\Http\Controllers\Controller;
use App\Models\Horaire;
use App\Models\Calendrier;
use App\Models\Jour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HoraireController extends Controller
{
    private function checkAdmin()
    {
        $user = auth()->user();

        if (
            !$user ||
            !in_array($user->role->role, ['admin', 'superadmin'])
        ) {
            abort(403, 'Accès refusé');
        }
    }

    public function index()
    {
        return Horaire::with(['jour', 'service', 'personnels', 'calendrier', 'reservations.user'])->get();
    }

    public function show(Horaire $horaire)
    {
        return $horaire->load(['jour', 'service', 'personnels', 'calendrier', 'reservations.user']);
    }

    public function store(Request $request)
    {
        $this->checkAdmin();

        $validator = Validator::make($request->all(), [
            'jour_numero' => 'required|integer|min:1|max:7',
            'heure_debut' => 'required',
            'heure_fin' => 'required|after:heure_debut',
            'nbre_clients' => 'required|integer|min:1',
            'calendrier_id' => 'required|exists:calendriers,id',
            'service_id' => 'required|exists:services,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Trouver le jour via le numéro
        $jour = Jour::where('numero', $request->jour_numero)->first();

        if (!$jour) {
            return response()->json(['message' => 'Jour invalide'], 404);
        }

        // Création horaire SANS restriction de chevauchement
        $horaire = Horaire::create([
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'nbre_clients' => $request->nbre_clients,
            'jour_id' => $jour->id,
            'calendrier_id' => $request->calendrier_id,
            'service_id' => $request->service_id,
        ]);


        return response()->json([
            'message' => 'Horaire créé avec succès',
            'data' => $horaire->load(['jour', 'service', 'calendrier'])
        ], 201);
    }

    public function update(Request $request, Horaire $horaire)
    {
        $this->checkAdmin();

        $validator = Validator::make($request->all(), [
            'jour_numero' => 'sometimes|integer|min:1|max:7',
            'heure_debut' => 'sometimes',
            'heure_fin' => 'sometimes|after:heure_debut',
            'nbre_clients' => 'sometimes|integer|min:1',
            'service_id' => 'sometimes|exists:services,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // Si on change le numéro du jour
        if ($request->has('jour_numero')) {
            $jour = Jour::where('numero', $request->jour_numero)->first();
            if (!$jour) {
                return response()->json(['message' => 'Jour invalide'], 404);
            }
            $data['jour_id'] = $jour->id;
            unset($data['jour_numero']);
        }


        $horaire->update($data);

        return response()->json([
            'message' => 'Horaire mis à jour',
            'data' => $horaire->load(['jour', 'service', 'calendrier'])
        ]);
    }

    public function destroy(Horaire $horaire)
    {
        $this->checkAdmin();
        $horaire->delete();

        return response()->json(['message' => 'Horaire supprimé']);
    }
}
