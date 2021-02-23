<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('paciente_id')->index();
            $table->date('fecha');
            $table->text('motivo')->nullable();
            $table->text('exploracion')->nullable();
            $table->text('juicio')->nullable();
            $table->text('tratamiento')->nullable();
            $table->text('notas')->nullable();
            $table->boolean('diagnosticado')->default(false);
            $table->boolean('informe')->default(true);
            $table->string('cie', 15)->nullable();
            $table->unsignedInteger('facultativo_id')->nullable();
            $table->string('username', 30);
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
        Schema::dropIfExists('historias');
    }
}
