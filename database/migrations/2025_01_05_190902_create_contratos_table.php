<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion')->nullable();
            $table->double('costo_total')->nullable();
            $table->string('estado')->nullable();
            $table->dateTime('fecha_inicio')->nullable();
            $table->dateTime('fecha_fin')->nullable();
            $table->string('estado_pago')->nullable()->default('PENDIENTE'); // PENDIENTE, PAGADO, PARCIAL
            $table->double('monto_pagado')->nullable()->default(0);
            $table->double('monto_saldo')->nullable()->default(0);
            $table->timestamps();
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->unsignedBigInteger('servicio_id')->nullable();
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('servicio_id')->references('id')->on('servicios')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
