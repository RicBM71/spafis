<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 50)->nullable();
            $table->decimal('importe', 5, 2)->default(0);
            $table->unsignedInteger('tratamiento_id');
            $table->unsignedInteger('sesiones')->default(10);
            $table->unsignedInteger('caducidad')->default(365);
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
        Schema::dropIfExists('bonos');
    }
}
