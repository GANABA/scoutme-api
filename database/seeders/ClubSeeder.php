<?php

namespace Database\Seeders;

use App\Models\Club;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Club::create(['nom' => 'Paris Saint-Germain', 'pays' => 'France', 'niveau' => 'Professionnel (D1)']);
        Club::create(['nom' => 'Olympique de Marseille', 'pays' => 'France', 'niveau' => 'Professionnel (D1)']);
        Club::create(['nom' => 'Académie Génération Foot', 'pays' => 'Sénégal', 'niveau' => 'Formation']);
    }
}
