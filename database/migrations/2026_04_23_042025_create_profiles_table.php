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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();

            // link to users table
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // basic info
            $table->string('full_name');
            $table->string('mobile');

            $table->string('city');
            $table->string('country');

            // social links
            $table->string('linkedin')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();

            // academic
            $table->string('degree');
            $table->string('branch');
            $table->year('passing_year');

            // professional summary
            $table->string('current_status')->nullable();
            $table->string('company')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};