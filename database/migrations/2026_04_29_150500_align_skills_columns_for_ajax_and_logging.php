<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('skills')) {
            return;
        }

        Schema::table('skills', function (Blueprint $table) {
            if (!Schema::hasColumn('skills', 'level')) {
                $table->string('level')->default('beginner')->after('name');
            }

            if (!Schema::hasColumn('skills', 'endorsements_count')) {
                $table->unsignedInteger('endorsements_count')->default(0)->after('level');
            }
        });

        if (Schema::hasColumn('skills', 'endorsements') && Schema::hasColumn('skills', 'endorsements_count')) {
            DB::table('skills')->update([
                'endorsements_count' => DB::raw('COALESCE(endorsements, 0)'),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('skills')) {
            return;
        }

        Schema::table('skills', function (Blueprint $table) {
            if (Schema::hasColumn('skills', 'endorsements_count')) {
                $table->dropColumn('endorsements_count');
            }

            if (Schema::hasColumn('skills', 'level')) {
                $table->dropColumn('level');
            }
        });
    }
};
