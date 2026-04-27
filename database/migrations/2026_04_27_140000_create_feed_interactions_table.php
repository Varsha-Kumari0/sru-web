<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feed_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('feed_type');
            $table->unsignedBigInteger('feed_id');
            $table->string('reaction')->default('like');
            $table->timestamps();

            $table->unique(['user_id', 'feed_type', 'feed_id', 'reaction'], 'feed_reactions_unique');
            $table->index(['feed_type', 'feed_id']);
        });

        Schema::create('feed_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('feed_type');
            $table->unsignedBigInteger('feed_id');
            $table->text('body');
            $table->timestamps();

            $table->index(['feed_type', 'feed_id']);
        });

        Schema::create('feed_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('feed_type');
            $table->unsignedBigInteger('feed_id');
            $table->timestamps();

            $table->index(['feed_type', 'feed_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feed_shares');
        Schema::dropIfExists('feed_comments');
        Schema::dropIfExists('feed_reactions');
    }
};
