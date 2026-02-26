<?php

namespace App\Http\Controllers\admin\utilisateurs;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;

class UtilisateurController extends Controller
{
    /**
     * Vérifie si superadmin
     */
    private function checkSuperAdmin()
    {
        $user = auth()->user();

        if (!$user || !$user->role || $user->role->role !== 'superadmin') {
            abort(403, 'Accès réservé au super administrateur');
        }
    }

    /**
     * Liste des admins
     */
    public function index()
    {
        $this->checkSuperAdmin();

        $roles = Role::whereIn('role', ['admin', 'personnel', 'user'])->pluck('id');

        $users = User::whereIn('role_id', $roles)
            ->with('role')
            ->latest()
            ->get();

        return UserResource::collection($users);
    }

    /**
     * Liste des clients uniquement (role=user)
     */
    public function clients()
    {
        $userRole = Role::where('role', 'user')->first();
        if (!$userRole) {
            return response()->json(['data' => []]);
        }
        $clients = User::where('role_id', $userRole->id)->with('role')->latest()->get();
        return UserResource::collection($clients);
    }

    /**
     * Récupérer tous les utilisateurs
     */
    public function allUsers()
    {
        $user = auth()->user();

        if (!$user || $user->role->role === 'user') {
            abort(403, 'Accès refusé');
        }

        $users = User::with('role', 'commandes')->latest()->get();
        return UserResource::collection($users);
    }

    /**
     * Créer un admin (rôle forcé à admin)
     */
    public function store(Request $request)
    {
        $this->checkSuperAdmin();

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'tel' => 'nullable|string|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'nullable|string|exists:roles,role',
        ], [
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'tel.unique' => 'Ce numéro de téléphone est déjà utilisé.',
            'role.exists' => 'Le rôle spécifié est invalide.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        // Récupère le rôle (par défaut admin si non spécifié)
        $roleName = $request->input('role', 'admin');
        $role = Role::where('role', $roleName)->firstOrFail();

        $user = User::create([
            'nom' => $request->nom,
            'email' => $request->email,
            'tel' => $request->tel,
            'password' => Hash::make($request->password),
            'role_id' => $role->id,
        ]);

        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'data' => new UserResource($user->load('role'))
        ], 201);
    }

    /**
     * Détails d'un admin
     */
    public function show(User $user)
    {
        $this->checkSuperAdmin();

        return new UserResource($user->load('role'));
    }

    /**
     * Modifier un admin
     */
    public function update(Request $request, User $user)
    {
        $this->checkSuperAdmin();

        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'tel' => 'sometimes|string|unique:users,tel,' . $user->id,
            'password' => 'nullable|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return response()->json([
            'message' => 'Administrateur modifié avec succès',
            'data' => new UserResource($user->load('role'))
        ]);
    }

    /**
     * Supprimer un admin
     */
    public function destroy(User $user)
    {
        $this->checkSuperAdmin();

        // Empêcher de supprimer un superadmin
        if ($user->role->role === 'superadmin') {
            return response()->json([
                'message' => 'Impossible de supprimer un super administrateur'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'message' => 'Administrateur supprimé avec succès'
        ]);
    }

    /**
     * Transformer un user en admin
     */
    public function makeAdmin(User $user)
    {
        $this->checkSuperAdmin();

        $adminRole = Role::where('role', 'admin')->firstOrFail();

        $user->update([
            'role_id' => $adminRole->id
        ]);

        return response()->json([
            'message' => 'Utilisateur promu administrateur',
            'data' => new UserResource($user->load('role'))
        ]);
    }

    /**
     * Rétrograder admin en user
     */
    public function makeUser(User $user)
    {
        $this->checkSuperAdmin();

        if ($user->role->role === 'superadmin') {
            return response()->json([
                'message' => 'Impossible de rétrograder un superadmin'
            ], 403);
        }

        $userRole = Role::where('role', 'user')->firstOrFail();

        $user->update([
            'role_id' => $userRole->id
        ]);

        return response()->json([
            'message' => 'Administrateur rétrogradé en utilisateur',
            'data' => new UserResource($user->load('role'))
        ]);
    }
}
