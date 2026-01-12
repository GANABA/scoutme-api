<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            // Afrique de l'Ouest (priorité ScoutMe)
            ['name_fr' => 'Bénin', 'name_en' => 'Benin', 'iso2' => 'BJ', 'iso3' => 'BEN', 'phone_code' => '+229'],
            ['name_fr' => 'Burkina Faso', 'name_en' => 'Burkina Faso', 'iso2' => 'BF', 'iso3' => 'BFA', 'phone_code' => '+226'],
            ['name_fr' => 'Cap-Vert', 'name_en' => 'Cape Verde', 'iso2' => 'CV', 'iso3' => 'CPV', 'phone_code' => '+238'],
            ['name_fr' => 'Côte d\'Ivoire', 'name_en' => 'Ivory Coast', 'iso2' => 'CI', 'iso3' => 'CIV', 'phone_code' => '+225'],
            ['name_fr' => 'Gambie', 'name_en' => 'Gambia', 'iso2' => 'GM', 'iso3' => 'GMB', 'phone_code' => '+220'],
            ['name_fr' => 'Ghana', 'name_en' => 'Ghana', 'iso2' => 'GH', 'iso3' => 'GHA', 'phone_code' => '+233'],
            ['name_fr' => 'Guinée', 'name_en' => 'Guinea', 'iso2' => 'GN', 'iso3' => 'GIN', 'phone_code' => '+224'],
            ['name_fr' => 'Guinée-Bissau', 'name_en' => 'Guinea-Bissau', 'iso2' => 'GW', 'iso3' => 'GNB', 'phone_code' => '+245'],
            ['name_fr' => 'Liberia', 'name_en' => 'Liberia', 'iso2' => 'LR', 'iso3' => 'LBR', 'phone_code' => '+231'],
            ['name_fr' => 'Mali', 'name_en' => 'Mali', 'iso2' => 'ML', 'iso3' => 'MLI', 'phone_code' => '+223'],
            ['name_fr' => 'Mauritanie', 'name_en' => 'Mauritania', 'iso2' => 'MR', 'iso3' => 'MRT', 'phone_code' => '+222'],
            ['name_fr' => 'Niger', 'name_en' => 'Niger', 'iso2' => 'NE', 'iso3' => 'NER', 'phone_code' => '+227'],
            ['name_fr' => 'Nigeria', 'name_en' => 'Nigeria', 'iso2' => 'NG', 'iso3' => 'NGA', 'phone_code' => '+234'],
            ['name_fr' => 'Sénégal', 'name_en' => 'Senegal', 'iso2' => 'SN', 'iso3' => 'SEN', 'phone_code' => '+221'],
            ['name_fr' => 'Sierra Leone', 'name_en' => 'Sierra Leone', 'iso2' => 'SL', 'iso3' => 'SLE', 'phone_code' => '+232'],
            ['name_fr' => 'Togo', 'name_en' => 'Togo', 'iso2' => 'TG', 'iso3' => 'TGO', 'phone_code' => '+228'],

            // Autres pays africains majeurs (football)
            ['name_fr' => 'Cameroun', 'name_en' => 'Cameroon', 'iso2' => 'CM', 'iso3' => 'CMR', 'phone_code' => '+237'],
            ['name_fr' => 'Maroc', 'name_en' => 'Morocco', 'iso2' => 'MA', 'iso3' => 'MAR', 'phone_code' => '+212'],
            ['name_fr' => 'Algérie', 'name_en' => 'Algeria', 'iso2' => 'DZ', 'iso3' => 'DZA', 'phone_code' => '+213'],
            ['name_fr' => 'Tunisie', 'name_en' => 'Tunisia', 'iso2' => 'TN', 'iso3' => 'TUN', 'phone_code' => '+216'],
            ['name_fr' => 'Égypte', 'name_en' => 'Egypt', 'iso2' => 'EG', 'iso3' => 'EGY', 'phone_code' => '+20'],
            ['name_fr' => 'Afrique du Sud', 'name_en' => 'South Africa', 'iso2' => 'ZA', 'iso3' => 'ZAF', 'phone_code' => '+27'],
            ['name_fr' => 'République Démocratique du Congo', 'name_en' => 'Democratic Republic of the Congo', 'iso2' => 'CD', 'iso3' => 'COD', 'phone_code' => '+243'],

            // Pays européens majeurs (destinations recruteurs)
            ['name_fr' => 'France', 'name_en' => 'France', 'iso2' => 'FR', 'iso3' => 'FRA', 'phone_code' => '+33'],
            ['name_fr' => 'Belgique', 'name_en' => 'Belgium', 'iso2' => 'BE', 'iso3' => 'BEL', 'phone_code' => '+32'],
            ['name_fr' => 'Espagne', 'name_en' => 'Spain', 'iso2' => 'ES', 'iso3' => 'ESP', 'phone_code' => '+34'],
            ['name_fr' => 'Italie', 'name_en' => 'Italy', 'iso2' => 'IT', 'iso3' => 'ITA', 'phone_code' => '+39'],
            ['name_fr' => 'Allemagne', 'name_en' => 'Germany', 'iso2' => 'DE', 'iso3' => 'DEU', 'phone_code' => '+49'],
            ['name_fr' => 'Royaume-Uni', 'name_en' => 'United Kingdom', 'iso2' => 'GB', 'iso3' => 'GBR', 'phone_code' => '+44'], // ✅ CORRIGÉ
            ['name_fr' => 'Portugal', 'name_en' => 'Portugal', 'iso2' => 'PT', 'iso3' => 'PRT', 'phone_code' => '+351'],
            ['name_fr' => 'Pays-Bas', 'name_en' => 'Netherlands', 'iso2' => 'NL', 'iso3' => 'NLD', 'phone_code' => '+31'],
        ];

        DB::table('countries')->insert($countries);

        $this->command->info(count($countries) . ' pays insérés avec succès.');
    }
}
