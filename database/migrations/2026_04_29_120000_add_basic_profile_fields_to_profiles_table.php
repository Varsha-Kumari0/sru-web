<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('user_id');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('gender')->nullable()->after('last_name');
            $table->string('contact_email')->nullable()->after('mobile');
            $table->string('pursuing_educational_level')->nullable()->after('current_status');
            $table->string('highest_completed_educational_level')->nullable()->after('pursuing_educational_level');
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'gender',
                'contact_email',
                'pursuing_educational_level',
                'highest_completed_educational_level',
            ]);
        });
    }
};