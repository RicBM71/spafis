<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebusersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webusers', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->string('password')->nullable();
            $table->date('password_at')->nullable();
            $table->unsignedInteger('facultativo_id')->nullable();
            $table->smallInteger('maxses')->default(1);
            $table->smallInteger('maxdias')->default(15);
            $table->smallInteger('intentos')->default(0);
            $table->boolean('blocked')->default(false);
            $table->boolean('traza')->default(false);
            $table->boolean('bono')->default(false);
            $table->boolean('condiciones')->default(false);
            $table->timestamp('login_at')->nullable();
            $table->string('ip',25)->nullable();
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
        Schema::dropIfExists('webusers');
    }
}
