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
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['doctor', 'gym', 'laboratory', 'pharmacy', 'nutritionist', 'psychologist']);
            $table->text('description')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('website')->nullable();
            $table->string('license_number')->nullable();
            $table->string('specialization')->nullable();
            $table->enum('status', ['active', 'inactive', 'pending'])->default('pending');
            $table->string('contact_person')->nullable();
            $table->string('logo')->nullable();
            $table->decimal('rating', 2, 1)->default(0.0);
            $table->json('opening_hours')->nullable();
            $table->json('services')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['type', 'status']);
            $table->index(['city', 'type']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
