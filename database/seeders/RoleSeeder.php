<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['superadmin', 'admin', 'user', 'personnel', 'caissier'];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['role' => $role],
                ['id' => (string) Str::uuid()]
            );
        }
    }
}
