<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePacientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 50);
            $table->string('apellidos', 50);
            $table->string('direccion', 50)->nullable();
            $table->string('cpostal', 5)->nullable();
            $table->string('poblacion', 50)->nullable();
            $table->string('provincia', 50)->nullable();
            $table->string('telefono1', 15)->nullable()->index();
            $table->string('telefono2', 15)->nullable()->index();
            $table->string('telefonom', 15)->nullable()->index();
            $table->string('notificar', 1)->default('N');
            $table->string('texto_tf2', 50)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('cif', 15)->nullable();
            $table->string('sexo', 1)->default('M');
            $table->date('fecha_nacimiento')->nullable();
            $table->decimal('descuento', 5, 2)->default(0);
            $table->boolean('porcentual')->default(false);
            $table->string('profesion', 50)->nullable();
            $table->boolean('tarifa_reducidad')->default(false);
            $table->unsignedInteger('mutua_id')->nullable();
            $table->date('fecha_baja')->nullable();
            $table->boolean('exportar')->default(false);
            $table->decimal('riesgo', 10, 2)->default(0);
            $table->text('notas1')->nullable();
            $table->text('notas2')->nullable();
            $table->unsignedInteger('medio_id')->nullable();
            $table->unsignedInteger('recomendado_id')->nullable();
            $table->text('ant1')->nullable();
            $table->text('ant2')->nullable();
            $table->text('ant3')->nullable();
            $table->text('ant4')->nullable();
            $table->text('ant5')->nullable();
            $table->text('ant6')->nullable();
            $table->text('antobs')->nullable();
            $table->boolean('embarazada')->default(false);
            $table->string('peso', 10)->nullable();
            $table->string('altura', 10)->nullable();
            $table->boolean('lortad_evo')->default(true);
            $table->boolean('lortad_fide')->default(false);
            $table->text('notas_adm')->nullable();
            $table->boolean('espera')->default(false);
            $table->boolean('factura_auto')->default(true);
            $table->string('username', 30)->nullable();
            $table->timestamps();

            $table->index(['nombre', 'apellidos']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pacientes');
    }
}
