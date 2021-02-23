<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTratamientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tratamientos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 50)->nullable();
            $table->string('nombre_web', 50)->nullable();
            $table->string('nombre_reducido', 5)->nullable();
            $table->decimal('importe', 10, 2)->default(0);
            $table->decimal('importe_reducido', 10, 2)->default(0);
            $table->decimal('precio_coste', 10, 2)->default(0);
            $table->unsignedInteger('duracion_manual')->default(30);
            $table->unsignedInteger('duracion_aparatos')->default(0);
            $table->unsignedInteger('edad')->default(0);
            $table->boolean('tpv')->default(false);
            $table->boolean('inventario')->default(false);
            $table->boolean('activo')->default(true);
            $table->boolean('bono')->default(true);
            $table->unsignedInteger('iva_id')->default(1);
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
        Schema::dropIfExists('tratamientos');
    }
}
