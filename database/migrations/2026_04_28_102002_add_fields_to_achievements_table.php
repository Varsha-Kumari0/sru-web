<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('achievements', function (Blueprint $table) {
        $table->string('category')->default('other')->after('title');
        $table->string('proof_url')->nullable()->after('description');
        $table->string('source')->default('auto')->after('proof_url');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('achievements', function (Blueprint $table) {
            //
        });
    }
};
