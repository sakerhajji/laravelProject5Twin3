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
        Schema::create('asymptomes', function (Blueprint $table) {
            $table->id();
            $table->string('nom');                // Nom du symptôme
            $table->text('description')->nullable(); // Description du symptôme
            $table->string('gravite')->nullable();   // Légère, modérée, sévère
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->softDeletes(); // Ajout pour SoftDeletes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asymptomes');
    }
};
