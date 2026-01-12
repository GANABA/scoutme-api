<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Country;
use Illuminate\Database\Seeder;

class ClubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les pays par ISO2 (sûr et stable)
        $espagne = Country::where('iso2', 'ES')->first();
        $france = Country::where('iso2', 'FR')->first();
        $rdc = Country::where('iso2', 'CD')->first(); // République Démocratique du Congo
        $senegal = Country::where('iso2', 'SN')->first();

        // Vérifier que tous les pays existent
        if (!$espagne || !$france || !$rdc || !$senegal) {
            $this->command->error('Erreur : Certains pays sont manquants. Exécutez CountrySeeder d\'abord.');
            return;
        }

        $clubs = [
            [
                'nom' => 'FC Barcelone',
                'country_id' => $espagne->id,
                'niveau' => 'professionnel',
                'site_web' => 'https://www.fcbarcelona.com',
            ],
            [
                'nom' => 'Real Madrid',
                'country_id' => $espagne->id,
                'niveau' => 'professionnel',
                'site_web' => 'https://www.realmadrid.com',
            ],
            [
                'nom' => 'Paris Saint-Germain',
                'country_id' => $france->id,
                'niveau' => 'professionnel',
                'site_web' => 'https://www.psg.fr',
            ],
            [
                'nom' => 'AS Vita Club',
                'country_id' => $rdc->id,
                'niveau' => 'professionnel',
                'site_web' => null,
            ],
            [
                'nom' => 'Académie Foot Dakar',
                'country_id' => $senegal->id,
                'niveau' => 'académie',
                'site_web' => null,
            ],
            [
                'nom' => 'Club Sportif Local',
                'country_id' => $france->id,
                'niveau' => 'amateur',
                'site_web' => null,
            ],
        ];

        foreach ($clubs as $club) {
            Club::create($club);
        }

        $this->command->info('Clubs créés avec succès.');
    }
}
