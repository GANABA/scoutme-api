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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name_fr')->comment('Nom du pays en français');
            $table->string('name_en')->comment('Nom du pays en anglais');
            $table->char('iso2', 2)->unique()->comment('Code ISO 3166-1 alpha-2');
            $table->char('iso3', 3)->unique()->comment('Code ISO 3166-1 alpha-3');
            $table->string('phone_code')->nullable()->comment('Indicatif téléphonique');
            $table->timestamps();

            // Index pour optimiser les recherches
            $table->index('name_fr');
            $table->index('name_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
