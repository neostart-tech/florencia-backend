<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer le rôle superadmin
        $superAdminRole = Role::where('role', 'superadmin')->first();

        if (!$superAdminRole) {
            $this->command->error("Le rôle superadmin n'existe pas !");
            return;
        }

        // Vérifier si le superadmin existe déjà
        $exists = User::where('email', 'admin@florencia.com')->first();

        if ($exists) {
            $this->command->info("Le super admin existe déjà.");
            return;
        }

        // Créer le super admin
        User::create([
            'id' => (string) Str::uuid(),
            'nom' => 'Super Admin',
            'email' => 'admin@florencia.com',
            'tel' => '90000000',
            'password' => Hash::make('Mot@de@passe'),
            'role_id' => $superAdminRole->id,
        ]);

        $this->command->info("Super admin créé avec succès !");
    }
}
