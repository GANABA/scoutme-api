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
        Schema::create('candidatures', function (Blueprint $table) {
            $table->id();

            // Annonce concernée
            $table->foreignId('annonce_id')
                ->constrained('annonces_recrutement')
                ->onDelete('cascade');

            // Joueur candidat
            $table->foreignId('joueur_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->comment('ID du joueur (User avec role=joueur)');

            // Message de motivation
            $table->text('message')->nullable();

            // Statut de la candidature
            $table->enum('statut', ['envoyee', 'en_cours', 'retenue', 'refusee'])
                ->default('envoyee');

            $table->timestamps();

            // Index pour optimiser les recherches
            $table->index('statut');
            $table->index('created_at');

            // Contrainte : un joueur ne peut postuler qu'une seule fois à une annonce
            $table->unique(['annonce_id', 'joueur_id'], 'unique_candidature');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidatures');
    }
};
