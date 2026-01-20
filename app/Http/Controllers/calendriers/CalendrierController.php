<?php

namespace App\Http\Controllers\calendriers;

use App\Http\Controllers\Controller;
use App\Models\Calendrier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CalendrierController extends Controller
{
    private function checkNotUser()
    {
        $user = auth()->user();
        if (!$user || $user->role->role === 'user') {
            abort(403, 'Accès refusé');
        }
    }

    public function index()
    {
        return Calendrier::with('horaires.jour')->latest()->get();
    }

    public function show(Calendrier $calendrier)
    {
        return $calendrier->load('horaires.jour');
    }

    public function store(Request $request)
    {
        $this->checkNotUser();

        $validator = Validator::make($request->all(), [
            'debut' => 'required|date',
            'fin' => 'required|date|after_or_equal:debut',
        ]);

        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()], 422);

        // Désactiver les autres calendriers
        Calendrier::where('is_active', true)->update(['is_active' => false]);

        $calendrier = Calendrier::create([
            ...$validator->validated(),
            'is_active' => true
        ]);

        return response()->json([
            'message' => 'Calendrier créé',
            'data' => $calendrier
        ], 201);
    }

    public function update(Request $request, Calendrier $calendrier)
    {
        $this->checkNotUser();

        $validator = Validator::make($request->all(), [
            'debut' => 'sometimes|date',
            'fin' => 'sometimes|date|after_or_equal:debut',
            'is_active' => 'sometimes|boolean'
        ]);

        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()], 422);

        if ($request->has('is_active') && $request->is_active == true) {
            Calendrier::where('is_active', true)->update(['is_active' => false]);
        }

        $calendrier->update($validator->validated());

        return response()->json([
            'message' => 'Calendrier mis à jour',
            'data' => $calendrier
        ]);
    }

    public function destroy(Calendrier $calendrier)
    {
        $this->checkNotUser();

        $calendrier->delete();

        return response()->json(['message' => 'Calendrier supprimé']);
    }
}
