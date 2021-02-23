<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovicuentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movicuentas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('empresa_id');
            $table->unsignedInteger('cuenta_id');
            $table->date('fecha');
            $table->String('dh',1);
            $table->String('concepto');
            $table->String('nota')->nullable();
            $table->decimal('importe', 10, 2)->default(0);
            $table->boolean('validado')->default(false);
            $table->unsignedInteger('partida_id')->nullable();
            $table->String('username',20)->nullable()->default(null);
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
        Schema::dropIfExists('movicuentas');
    }
}
