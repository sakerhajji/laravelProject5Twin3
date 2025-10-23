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
        Schema::create('asymptome_maladie', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asymptome_id')->constrained('asymptomes')->onDelete('cascade');
            $table->foreignId('maladie_id')->constrained('maladies')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['asymptome_id', 'maladie_id']); // empêche les doublons
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asymptome_maladie');
    }
};
