<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('empresa_id');
            $table->integer('ejercicio');
            $table->integer('factura');
            $table->string('serie',1);
            $table->date('fecha');
            $table->unsignedInteger('paciente_id')->index();
            $table->unsignedInteger('mutua_id')->nullable();
            $table->string('razon', 50);
            $table->string('direccion', 50);
            $table->string('poblacion', 50);
            $table->integer('cpostal')->nullable();
            $table->string('provincia', 50);
            $table->string('cif', 10)->nullable();
            $table->unsignedInteger('cuenta_id')->nullable();
            $table->unsignedInteger('fpago_id');
            $table->text('notas')->nullable();
            $table->string('username', 30);
            $table->timestamps();

            $table->unique(['empresa_id', 'ejercicio', 'serie', 'factura']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facturas');
    }
}
