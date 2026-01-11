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
        Schema::table('joueurs', function (Blueprint $table) {
            // Supprimer les anciennes colonnes texte
            $table->dropColumn(['position', 'nationalite']);

            // Ajouter les FK
            $table->foreignId('position_id')->after('user_id')->constrained('positions')->onDelete('restrict');
            $table->foreignId('nationality_id')->after('pied_fort')->constrained('countries')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('joueurs', function (Blueprint $table) {
            // Retirer les FK
            $table->dropForeign(['position_id']);
            $table->dropForeign(['nationality_id']);
            $table->dropColumn(['position_id', 'nationality_id']);

            // Restaurer les anciennes colonnes
            $table->string('position')->after('user_id');
            $table->string('nationalite')->after('pied_fort');
        });
    }
};
