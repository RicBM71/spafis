<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMutuasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mutuas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 50);
            $table->string('direccion', 50)->nullable();
            $table->string('cpostal', 5)->nullable();
            $table->string('poblacion', 50)->nullable();
            $table->string('provincia', 50)->nullable();
            $table->string('telefono1', 15)->nullable();
            $table->string('telefono2', 15)->nullable();
            $table->string('telefonom', 15)->nullable();
            $table->string('cif', 10)->nullable()->unique();
            $table->string('email', 50)->nullable();
            $table->string('contacto', 50)->nullable();
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
        Schema::dropIfExists('mutuas');
    }
}
