<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCobrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cobros', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('empresa_id');
            $table->date('fecha');
            $table->unsignedInteger('area_id');
            $table->unsignedInteger('cita_id')->index();
            $table->unsignedInteger('paciente_id');
            $table->unsignedInteger('fpago_id');
            $table->unsignedInteger('tpv_id')->nullable();
            $table->decimal('importe', 10, 2)->default(0);
            $table->string('autorizacion', 30)->nullable();
            $table->string('username',30)->nullable();
            $table->timestamps();
            $table->index(['empresa_id', 'id']);
            $table->index(['empresa_id', 'cita_id']);
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
        Schema::dropIfExists('cobros');
    }
}
