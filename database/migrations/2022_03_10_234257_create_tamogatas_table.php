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
        Schema::create('tamogatas', function (Blueprint $table) {
            $table->id();
            $table->integer('ev');
            $table->string('name', 140);
            $table->string('irszam', 4);
            $table->string('varos', 40);
            $table->string('utca', 190);
            $table->bigInteger('osszeg');
            $table->bigInteger('evesosszeg');
            $table->boolean('is_firm');
            $table->boolean('is_landbased');
            $table->string('gender', 10);
            $table->decimal('point_lat', 10, 7);
            $table->decimal('point_long', 10, 7);
            $table->foreignIdFor(\App\Models\Cegcsoport::class)->constrained();
            $table->foreignIdFor(\App\Models\Tamogatott::class)->constrained();
            $table->foreignIdFor(\App\Models\Jogcim::class)->constrained();
            $table->foreignIdFor(\App\Models\Alap::class)->constrained();
            $table->foreignIdFor(\App\Models\Forras::class)->constrained();
            $table->foreignIdFor(\App\Models\Megye::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tamogatas');
    }
};
