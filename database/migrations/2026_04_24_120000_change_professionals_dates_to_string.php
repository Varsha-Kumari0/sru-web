<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('professionals', function (Blueprint $table) {
            // Change from date to string to accept both dates and text like 'Present'
            $table->string('from')->change();
            $table->string('to')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('professionals', function (Blueprint $table) {
            // Revert back to date columns
            $table->date('from')->change();
            $table->date('to')->nullable()->change();
        });
    }
};
