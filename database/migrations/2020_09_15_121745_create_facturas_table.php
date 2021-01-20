<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();            
            $table->unsignedBigInteger('numero');

            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->foreign('cliente_id')->references('id')->on('clientes');

            $table->unsignedBigInteger('repartidor_id')->nullable();
            $table->foreign('repartidor_id')->references('id')->on('empleados');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
         
            $table->decimal('importe',10,2);
            $table->enum('estado', ['ABIERTA','PAGADA', 'PENDIENTE', 'CTACTE', 'ANULADA'])->default('ABIERTA');

            $table->unsignedBigInteger('comercio_id');
            $table->foreign('comercio_id')->references('id')->on('comercios');

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
        Schema::dropIfExists('facturas');
    }
}
