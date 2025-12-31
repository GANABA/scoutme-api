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
        Schema::create('joueurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('position'); // Attaquant, etc.
            $table->date('date_naissance');
            $table->integer('taille')->comment('en cm');
            $table->enum('pied_fort', ['Droit', 'Gauche', 'Ambidextre']);
            $table->string('nationalite');
            $table->foreignId('club_actuel_id')->nullable()->constrained('clubs')->onDelete('set null');
            $table->text('motivation')->nullable();
            $table->string('photo_profil')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('joueurs');
    }
};
