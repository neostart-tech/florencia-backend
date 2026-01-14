<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['superadmin', 'admin', 'user'];

        foreach ($roles as $role) {
            Role::create([
                'id' => (string) Str::uuid(),
                'role' => $role,
            ]);
        }
    }
}
