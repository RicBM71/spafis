<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('empresa_id');
            $table->unsignedInteger('area_id');
            $table->unsignedInteger('paciente_id')->index();
            $table->date('fecha');
            $table->time('hora');
            $table->unsignedInteger('facultativo_id');
            $table->unsignedInteger('estado_id');
            $table->unsignedInteger('tratamiento_id');
            $table->decimal('importe', 10, 2)->default(0);
            $table->decimal('importe_ponderado', 10, 2)->default(0);
            $table->decimal('iva', 5, 2)->default(0);
            $table->integer('bono')->nullable()->index();
            $table->integer('apunte')->nullable();
            $table->date('fecha_cobro')->nullable();
            $table->integer('factura_id')->nullable();
            $table->integer('ejercicio')->nullable();
            $table->smallInteger('sesiones')->nullable();
            $table->unsignedInteger('mutua_id')->nullable();
            $table->string('autorizacion', 30)->nullable();
            $table->string('tipo_envio', 1)->nullable();
            $table->dateTime('envio_sms', 1)->nullable();
            $table->string('sms_id', 50)->nullable();
            $table->text('notas')->nullable();
            $table->boolean('tune')->default(false);
            $table->string('username',30)->nullable();
            $table->timestamps();

            $table->index(['empresa_id', 'id']);
            $table->index(['empresa_id', 'area_id', 'fecha']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('citas');
    }
}
