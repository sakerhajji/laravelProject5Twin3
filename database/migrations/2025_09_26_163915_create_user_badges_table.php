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
        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('objective_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('badge_type'); // streak_7, streak_30, goal_achieved, etc.
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('icon')->default('fas fa-trophy');
            $table->string('color')->default('gold');
            $table->json('metadata')->nullable(); // Données supplémentaires (valeur, date, etc.)
            $table->timestamp('earned_at');
            $table->timestamps();
            
            $table->index(['user_id', 'badge_type']);
            $table->index(['user_id', 'objective_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_badges');
    }
};