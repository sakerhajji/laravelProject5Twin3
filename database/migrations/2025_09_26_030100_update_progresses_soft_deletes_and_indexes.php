<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('progresses', function (Blueprint $table) {
            if (!Schema::hasColumn('progresses', 'deleted_at')) {
                $table->softDeletes();
            }
            $table->index(['user_id','objective_id','entry_date']);
        });
    }

    public function down(): void
    {
        Schema::table('progresses', function (Blueprint $table) {
            if (Schema::hasColumn('progresses', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
            $table->dropIndex(['user_id','objective_id','entry_date']);
        });
    }
};


