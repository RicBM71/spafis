<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdjuntosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjuntos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('paciente_id')->index();
            $table->string('descripcion')->nullable();
            $table->string('path')->nullable();
            $table->boolean('leido')->default(false);
            $table->string('username',30)->nullable();
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
        Schema::dropIfExists('adjuntos');
    }
}
