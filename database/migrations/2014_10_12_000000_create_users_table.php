<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('apellido');
            $table->enum('sexo', ['1', '2']);
            $table->string('telefono1',15)->nullable();
            $table->string('telefono2',15)->nullable();
            $table->string('direccion')->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('pass')->nullable();
            $table->enum('abonado', ['Si', 'No', 'Admin'])->default('No');

            // $table->unsignedBigInteger('comercio_id');
            // $table->foreign('comercio_id')->references('id')->on('comercios');

            $table->rememberToken();
            $table->timestamps();
            // $table->enum('tipo', ['Empleado', 'Admin', 'Cliente'])->default('Empleado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
