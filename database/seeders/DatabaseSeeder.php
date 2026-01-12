<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use PHPUnit\Framework\Constraint\Count;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ordre important : les tables de référence d'abord
        $this->call([
            CountriesSeeder::class,
            PositionsSeeder::class,
            ClubSeeder::class,
            // Ajouter ici plus tard : ClubsSeeder si besoin
        ]);
    }
}
