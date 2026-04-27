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
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('study_institution')->nullable()->after('company');
            $table->string('study_degree')->nullable()->after('study_institution');
            $table->string('study_branch')->nullable()->after('study_degree');
            $table->string('study_from')->nullable()->after('study_branch');
            $table->string('study_to')->nullable()->after('study_from');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'study_institution',
                'study_degree',
                'study_branch',
                'study_from',
                'study_to',
            ]);
        });
    }
};
