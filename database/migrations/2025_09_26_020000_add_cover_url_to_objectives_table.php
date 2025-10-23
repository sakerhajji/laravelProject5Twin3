<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('objectives', 'cover_url')) {
            Schema::table('objectives', function (Blueprint $table) {
                $table->string('cover_url')->nullable()->after('unit');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('objectives', 'cover_url')) {
            Schema::table('objectives', function (Blueprint $table) {
                $table->dropColumn('cover_url');
            });
        }
    }
};


