<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('annonces_recrutement', function (Blueprint $table) {
            // Supprimer l'ancienne colonne texte 'poste_recherche'
            $table->dropColumn('poste_recherche');

            // Ajouter la FK vers positions
            $table->foreignId('position_id')
                ->nullable()
                ->after('type')
                ->constrained('positions')
                ->onDelete('set null')
                ->comment('Position recherchée (normalisée)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('annonces_recrutement', function (Blueprint $table) {
            // Retirer la FK
            $table->dropForeign(['position_id']);
            $table->dropColumn('position_id');

            // Restaurer l'ancienne colonne
            $table->string('poste_recherche')
                ->nullable()
                ->after('type')
                ->comment('Position recherchée');
        });
    }
};
