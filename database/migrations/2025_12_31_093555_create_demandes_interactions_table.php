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
        Schema::create('demandes_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emetteur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('recepteur_id')->constrained('users')->onDelete('cascade');
            $table->text('message');
            $table->enum('statut', ['en_attente', 'accepte', 'refuse'])->default('en_attente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes_interactions');
    }
};
