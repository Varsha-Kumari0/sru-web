<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            // Change passing_year from YEAR to STRING to allow flexible year input
            $table->string('passing_year')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            // Revert back to YEAR column
            $table->year('passing_year')->change();
        });
    }
};
