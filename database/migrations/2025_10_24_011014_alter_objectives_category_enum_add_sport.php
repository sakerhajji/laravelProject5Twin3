<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // MySQL: modifier l'ENUM pour inclure 'sport'
        DB::statement("ALTER TABLE objectives MODIFY category ENUM('activite','nutrition','sommeil','sante','sport') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revenir à l'ENUM initial (sans 'sport')
        DB::statement("ALTER TABLE objectives MODIFY category ENUM('activite','nutrition','sommeil','sante') NOT NULL");
    }
};
