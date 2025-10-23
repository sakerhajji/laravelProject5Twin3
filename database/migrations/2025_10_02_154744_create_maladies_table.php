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
        Schema::create('maladies', function (Blueprint $table) {
            $table->id();
            $table->string('nom');                   // Nom de la maladie
            $table->text('description')->nullable(); // Description de la maladie
            $table->text('traitement')->nullable();  // Traitement de la maladie
            $table->text('prevention')->nullable();  // Mesures de prévention
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->softDeletes();                   // SoftDeletes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maladies');
    }
};
