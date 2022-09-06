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
        Schema::create('kereseslogs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->ipAddress();
            $table->string('endpoint',30);
            $table->text('url');
            $table->json('queryparameter');
            $table->integer('per_page')->nullable(true);
            $table->text('sqlquery')->nullable(true);
            $table->decimal('sqltime')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kereseslogs');
    }
};
