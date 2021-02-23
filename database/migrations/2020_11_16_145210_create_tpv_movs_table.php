<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpvMovsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tpv_movs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('empresa_id');
            $table->date('fecha');
            $table->unsignedInteger('paciente_id');
            $table->unsignedInteger('tpv_id');
            $table->string('tipo_pago');
            $table->string('moneda', 10);
            $table->string('comercio', 30);
            $table->string('pedido', 30);
            $table->dateTime('fecha_operacion');
            $table->string('titular_tarjeta')->nullable();
            $table->string('tarjeta')->nullable();
            $table->string('estado',1);
            $table->string('codigo_respuesta',20);
            $table->decimal('importe', 10, 2);
            $table->boolean('procesado')->default(false);
            $table->string('autenticado',60)->nullable();
            $table->string('username',30)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('tpv_movs');
    }
}
