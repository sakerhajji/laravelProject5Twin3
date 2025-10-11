<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('activities', function (Blueprint $table) {
        if (!Schema::hasColumn('activities', 'media_url')) {
            $table->string('media_url')->nullable();
        }

        if (!Schema::hasColumn('activities', 'media_type')) {
            $table->enum('media_type', ['image', 'video'])->default('image');
        }

        // Remove old 'image' column if it exists
        if (Schema::hasColumn('activities', 'image')) {
            $table->dropColumn('image');
        }
    });
}


    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->string('image')->nullable();
            $table->dropColumn(['media_url', 'media_type']);
        });
    }
};
