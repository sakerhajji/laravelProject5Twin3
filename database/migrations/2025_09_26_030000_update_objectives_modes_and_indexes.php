<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('objectives', function (Blueprint $table) {
            if (!Schema::hasColumn('objectives', 'mode')) {
                $table->enum('mode', ['cumulative','inverse','periodic'])->default('cumulative')->after('category');
            }
            if (!Schema::hasColumn('objectives', 'period')) {
                $table->enum('period', ['daily','weekly'])->nullable()->after('mode');
            }
            if (!Schema::hasColumn('objectives', 'deleted_at')) {
                $table->softDeletes();
            }
            $table->index('category');
        });

        Schema::table('user_objectives', function (Blueprint $table) {
            $table->index(['user_id','objective_id']);
        });
    }

    public function down(): void
    {
        Schema::table('objectives', function (Blueprint $table) {
            if (Schema::hasColumn('objectives', 'mode')) { $table->dropColumn('mode'); }
            if (Schema::hasColumn('objectives', 'period')) { $table->dropColumn('period'); }
            if (Schema::hasColumn('objectives', 'deleted_at')) { $table->dropSoftDeletes(); }
            $table->dropIndex(['category']);
        });

        Schema::table('user_objectives', function (Blueprint $table) {
            $table->dropIndex(['user_id','objective_id']);
        });
    }
};


