<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tpvs', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',50)->nullable();
            $table->string('comercio',50)->nullable();
            $table->string('puerto',50)->nullable();
            $table->smallInteger('terminal');
            $table->string('version',25)->nullable();
            $table->string('firma',50)->nullable();
            $table->string('nombre_comercio',50)->nullable();
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
        Schema::dropIfExists('tpvs');
    }
}
