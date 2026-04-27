<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_opportunities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('title');
            $table->string('company_name');
            $table->string('company_website')->nullable();
            $table->string('experience_level');
            $table->string('work_mode');
            $table->string('location')->nullable();
            $table->string('contact_email');
            $table->string('job_area');
            $table->json('skills')->nullable();
            $table->string('salary')->nullable();
            $table->date('application_deadline')->nullable();
            $table->longText('description');
            $table->string('attachment')->nullable();
            $table->string('attachment_original_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_opportunities');
    }
};
