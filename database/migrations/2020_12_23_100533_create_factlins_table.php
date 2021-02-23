<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactlinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factlins', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('factura_id')->index();
            $table->text('concepto');
            $table->decimal('iva', 5, 2)->default(0);
            $table->decimal('importe', 10, 2)->default(0);
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
        Schema::dropIfExists('factlins');
    }
}
