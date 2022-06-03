<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tamogatotts', function (Blueprint $table) {
            $table->string('irszam', 4)->nullable(true);
            $table->string('varos', 40)->nullable(true);
            $table->string('utca', 190)->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tamogatotts', function (Blueprint $table) {
            $table->dropColumn('irszam');
            $table->dropColumn('varos');
            $table->dropColumn('utca');
        });
    }
};
