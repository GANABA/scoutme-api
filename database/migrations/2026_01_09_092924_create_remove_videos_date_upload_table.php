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
        Schema::table('videos', function (Blueprint $table) {
            // Supprimer date_upload (utiliser created_at à la place)
            $table->dropColumn('date_upload');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            // Restaurer date_upload si besoin de rollback
            $table->date('date_upload')
                ->nullable()
                ->after('url_ou_fichier');
        });
    }
};
