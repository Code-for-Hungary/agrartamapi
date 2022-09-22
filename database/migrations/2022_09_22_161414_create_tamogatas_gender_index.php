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
        Schema::table('tamogatas', function (Blueprint $table) {
            $table->index('gender', 'tamogatas_gender');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tamogatas', function (Blueprint $table) {
            $table->dropIndex('tamogatas_gender');
        });
    }
};
