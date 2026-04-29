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
        if (Schema::hasTable('skill_endorsements')) {
            return;
        }

        Schema::create('skill_endorsements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skill_id')->constrained('skills')->cascadeOnDelete();
            $table->foreignId('endorser_id')->constrained('users')->cascadeOnDelete();
            $table->string('endorser_name');
            $table->timestamps();

            $table->unique(['skill_id', 'endorser_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_endorsements');
    }
};
