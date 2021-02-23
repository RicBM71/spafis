<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBloqueosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bloqueos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('empresa_id');
            $table->date('fecha');
            $table->unsignedInteger('facultativo_id');
            $table->time('start');
            $table->time('end');
            $table->string('motivo',30)->nullable();
            $table->boolean('remunerada')->default(true);
            $table->string('username',30)->nullable();
            $table->timestamps();
            $table->index(['empresa_id', 'fecha', 'facultativo_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bloqueos');
    }
}
