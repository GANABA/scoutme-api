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
        Schema::table('recruteurs', function (Blueprint $table) {
            // Supprimer l'ancienne colonne 'pays'
            $table->dropColumn('pays');

            // Ajouter la FK vers countries
            $table->foreignId('country_id')->after('nom_organisation')->constrained('countries')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recruteurs', function (Blueprint $table) {
            // Retirer la FK
            $table->dropForeign(['country_id']);
            $table->dropColumn('country_id');

            // Restaurer l'ancienne colonne
            $table->string('pays')->after('nom_organisation');
        });
    }
};
