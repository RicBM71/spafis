<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHorariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('facultativo_id')->index();
            $table->date('fecha');
            $table->time('inim_1', 0)->default('00:00:00');
            $table->time('finm_1', 0)->default('00:00:00');
            $table->time('init_1', 0)->default('00:00:00');
            $table->time('fint_1', 0)->default('00:00:00');
            $table->time('inim_2', 0)->default('00:00:00');
            $table->time('finm_2', 0)->default('00:00:00');
            $table->time('init_2', 0)->default('00:00:00');
            $table->time('fint_2', 0)->default('00:00:00');
            $table->time('inim_3', 0)->default('00:00:00');
            $table->time('finm_3', 0)->default('00:00:00');
            $table->time('init_3', 0)->default('00:00:00');
            $table->time('fint_3', 0)->default('00:00:00');
            $table->time('inim_4', 0)->default('00:00:00');
            $table->time('finm_4', 0)->default('00:00:00');
            $table->time('init_4', 0)->default('00:00:00');
            $table->time('fint_4', 0)->default('00:00:00');
            $table->time('inim_5', 0)->default('00:00:00');
            $table->time('finm_5', 0)->default('00:00:00');
            $table->time('init_5', 0)->default('00:00:00');
            $table->time('fint_5', 0)->default('00:00:00');
            $table->time('inim_6', 0)->default('00:00:00');
            $table->time('finm_6', 0)->default('00:00:00');
            $table->time('init_6', 0)->default('00:00:00');
            $table->time('fint_6', 0)->default('00:00:00');
            $table->time('inim_0', 0)->default('00:00:00');
            $table->time('finm_0', 0)->default('00:00:00');
            $table->time('init_0', 0)->default('00:00:00');
            $table->time('fint_0', 0)->default('00:00:00');
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
        Schema::dropIfExists('horarios');
    }
}
