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
        Schema::table('telepules', function (Blueprint $table) {
            $table->index('irszam', 'telepules_irszam');
            $table->index('name', 'telepules_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('telepules', function (Blueprint $table) {
            $table->dropIndex([
                'telepules_irszam',
                'telepules_name'
            ]);
        });
    }
};
