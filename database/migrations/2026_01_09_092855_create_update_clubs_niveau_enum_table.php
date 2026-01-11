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
        Schema::table('clubs', function (Blueprint $table) {
            // Modifier la colonne existante en enum
            $table->enum('niveau', ['professionnel', 'amateur', 'académie', 'régional'])
                ->comment('Niveau du club')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clubs', function (Blueprint $table) {
            // Revenir à un string
            $table->string('niveau')->change();
        });
    }
};
