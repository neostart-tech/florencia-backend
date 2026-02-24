<?php

namespace App\Http\Controllers\personnels;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PersonnelController extends Controller
{
    /**
     * Vérifie admin
     */
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

    /**
     * Récupère role autorisé
     */
    private function getRole(string $roleName): Role
    {
        if (!in_array($roleName, ['personnel', 'caissier'])) {
            abort(400, 'Rôle invalide');
        }

        return Role::where('role', $roleName)->firstOrFail();
    }

    private function resolveRole(Request $request): Role
    {
        $prefix = $request->route()->getPrefix();

        if (str_contains($prefix, 'personnels')) {
            $roleName = 'personnel';
        } elseif (str_contains($prefix, 'caissiers')) {
            $roleName = 'caissier';
        } else {
            abort(400, 'Type utilisateur invalide');
        }

        return Role::where('role', $roleName)->firstOrFail();
    }

    /**
     * Liste personnel / caissier
     */
    public function index(Request $request)
    {
        $this->checkAdmin();

        $role = $this->resolveRole($request);

        $role = $this->getRole($request->role);

        $users = User::where('role_id', $role->id)
            ->latest()
            ->get();

        return response()->json($users);
    }

    /**
     * Création personnel / caissier
     */
    public function store(Request $request)
    {
        $this->checkAdmin();

        $role = $this->resolveRole($request);

        $role = $this->getRole($request->role);

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'tel' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $plainPassword = Str::random(10);

        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'tel' => $request->tel,
            'password' => Hash::make($plainPassword),
            'role_id' => $role->id,
        ]);

        return response()->json([
            'message' => ucfirst($role->role) . ' créé avec succès',
            'password_temporaire' => $plainPassword,
            'data' => $user
        ], 201);
    }

    /**
     * Détails
     */
    public function show(Request $request, $id)
    {
        $this->checkAdmin();

        $role = $this->resolveRole($request);

        $role = $this->getRole($request->role);

        $user = User::where('id', $id)
            ->where('role_id', $role->id)
            ->firstOrFail();

        return response()->json($user);
    }

    /**
     * Update
     */
    public function update(Request $request, $id)
    {
        $this->checkAdmin();

        $role = $this->resolveRole($request);

        $role = $this->getRole($request->role);

        $user = User::where('id', $id)
            ->where('role_id', $role->id)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'tel' => 'sometimes|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update($validator->validated());

        return response()->json([
            'message' => ucfirst($role->role) . ' modifié avec succès',
            'data' => $user
        ]);
    }

    /**
     * Suppression
     */
    public function destroy(Request $request, $id)
    {
        $this->checkAdmin();

        $role = $this->resolveRole($request);

        $role = $this->getRole($request->role);

        $user = User::where('id', $id)
            ->where('role_id', $role->id)
            ->firstOrFail();

        $user->delete();

        return response()->json([
            'message' => ucfirst($role->role) . ' supprimé avec succès'
        ]);
    }
}
