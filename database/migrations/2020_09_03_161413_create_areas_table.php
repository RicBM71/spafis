<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 50);
            $table->time('hora1', 0)->default('00:00:00');
            $table->time('hora2', 0)->default('00:00:00');
            $table->time('tarde', 0)->default('00:00:00');
            $table->smallInteger('frecuencia')->default(30);
            $table->boolean('activo')->default(true);
            $table->string('username', 30)->nullable();
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
        Schema::dropIfExists('areas');
    }
}
