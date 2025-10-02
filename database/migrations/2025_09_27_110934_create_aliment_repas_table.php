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
        Schema::create('aliment_repas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aliment_id')->constrained()->onDelete('cascade');
            $table->foreignId('repas_id')->constrained()->onDelete('cascade');
            $table->decimal('quantite', 8, 2);
            $table->timestamps();

            $table->unique(['aliment_id', 'repas_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aliment_repas');
    }
};