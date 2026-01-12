<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            // Gardiens
            ['nom_fr' => 'Gardien de but', 'nom_en' => 'Goalkeeper', 'abreviation' => 'GB'],

            // Défenseurs
            ['nom_fr' => 'Défenseur central', 'nom_en' => 'Centre-back', 'abreviation' => 'DC'],
            ['nom_fr' => 'Défenseur latéral droit', 'nom_en' => 'Right-back', 'abreviation' => 'DD'],
            ['nom_fr' => 'Défenseur latéral gauche', 'nom_en' => 'Left-back', 'abreviation' => 'DG'],
            ['nom_fr' => 'Libéro', 'nom_en' => 'Sweeper', 'abreviation' => 'LIB'],

            // Milieux
            ['nom_fr' => 'Milieu défensif', 'nom_en' => 'Defensive midfielder', 'abreviation' => 'MD'],
            ['nom_fr' => 'Milieu central', 'nom_en' => 'Central midfielder', 'abreviation' => 'MC'],
            ['nom_fr' => 'Milieu offensif', 'nom_en' => 'Attacking midfielder', 'abreviation' => 'MO'],
            ['nom_fr' => 'Milieu droit', 'nom_en' => 'Right midfielder', 'abreviation' => 'MDR'],
            ['nom_fr' => 'Milieu gauche', 'nom_en' => 'Left midfielder', 'abreviation' => 'MG'],

            // Attaquants
            ['nom_fr' => 'Ailier droit', 'nom_en' => 'Right winger', 'abreviation' => 'AD'],
            ['nom_fr' => 'Ailier gauche', 'nom_en' => 'Left winger', 'abreviation' => 'AG'],
            ['nom_fr' => 'Avant-centre', 'nom_en' => 'Striker', 'abreviation' => 'AVC'],
            ['nom_fr' => 'Attaquant de soutien', 'nom_en' => 'Second striker', 'abreviation' => 'AS'],
        ];

        DB::table('positions')->insert($positions);
    }
}
