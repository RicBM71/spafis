<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacultativosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facultativos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 50);
            $table->string('apellidos', 50);
            $table->string('direccion', 50)->nullable();
            $table->string('cpostal', 5)->nullable();
            $table->string('poblacion', 50)->nullable();
            $table->string('provincia', 50)->nullable();
            $table->string('telefono1', 15)->nullable();
            $table->string('telefono2', 15)->nullable();
            $table->string('telefonom', 15)->nullable();
            $table->string('cif', 10)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('colegiado', 15)->nullable();
            $table->date('fecha_baja')->nullable();
            $table->String('iban',24)->nullable();
            $table->string('color', 15)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('alias', 15)->nullable();
            $table->string('numero_ss', 50)->nullable();
            $table->unsignedInteger('grupo_id')->default(1);
            $table->unsignedInteger('categoria_id');
            $table->integer('orden')->default(999);
            $table->integer('objetivo')->default(0);
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
        Schema::dropIfExists('facultativos');
    }
}
