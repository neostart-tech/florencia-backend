<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jour;
use Illuminate\Support\Str;

class JourSeeder extends Seeder
{
    public function run(): void
    {
        $jours = [
            ['nom' => 'Lundi', 'numero' => 1],
            ['nom' => 'Mardi', 'numero' => 2],
            ['nom' => 'Mercredi', 'numero' => 3],
            ['nom' => 'Jeudi', 'numero' => 4],
            ['nom' => 'Vendredi', 'numero' => 5],
            ['nom' => 'Samedi', 'numero' => 6],
            ['nom' => 'Dimanche', 'numero' => 0],
        ];

        foreach ($jours as $jour) {
            Jour::updateOrCreate(
                ['numero' => $jour['numero']],
                [
                    'id' => Str::uuid(),
                    'nom' => $jour['nom'],
                    'numero' => $jour['numero'],
                ]
            );
        }
    }
}
