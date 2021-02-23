<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePacbonosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pacbonos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('paciente_id')->index();
            $table->integer('bono')->index();
            $table->date('fecha');
            $table->smallInteger('sesiones');
            $table->unsignedInteger('tratamiento_id');
            $table->decimal('importe', 10, 2)->default(0);
            $table->smallInteger('caducidad');
            $table->boolean('caducado')->default(false);
            $table->string('texto', 100)->nullable();
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
        Schema::dropIfExists('pacbonos');
    }
}
