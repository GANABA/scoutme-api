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
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('nom_fr')->unique()->comment('Nom de la position en français');
            $table->string('nom_en')->unique()->comment('Nom de la position en anglais');
            $table->string('abreviation', 10)->unique()->comment('Abréviation (ex: ATT, MIL, DEF)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
