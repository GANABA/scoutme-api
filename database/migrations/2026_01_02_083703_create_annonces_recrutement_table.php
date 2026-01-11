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
        Schema::create('annonces_recrutement', function (Blueprint $table) {
            $table->id();

            // Recruteur propriétaire de l'annonce
            $table->foreignId('recruteur_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->comment('ID du recruteur (User avec role=recruteur)');

            // Club associé (optionnel)
            $table->foreignId('club_id')
                ->nullable()
                ->constrained('clubs')
                ->onDelete('set null');

            // Informations de l'annonce
            $table->string('titre');
            $table->text('description');
            $table->enum('type', ['recrutement', 'selection', 'test']);

            // Critères de recherche (optionnels)
            $table->string('poste_recherche')->nullable()->comment('Position recherchée');
            $table->integer('age_min')->nullable();
            $table->integer('age_max')->nullable();
            $table->integer('taille_min')->nullable()->comment('Taille minimale en cm');

            // Localisation (optionnel)
            $table->foreignId('country_id')
                ->nullable()
                ->constrained('countries')
                ->onDelete('set null')
                ->comment('Pays ciblé pour le recrutement');

            // Dates et statut
            $table->date('date_limite')->nullable()->comment('Date limite de candidature');
            $table->enum('statut', ['brouillon', 'publiee', 'fermee'])->default('brouillon');
            $table->enum('visibilite', ['publique', 'privee'])->default('publique');

            $table->timestamps();

            // Index pour optimiser les recherches
            $table->index('statut');
            $table->index('visibilite');
            $table->index('type');
            $table->index('date_limite');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annonces_recrutement');
    }
};
