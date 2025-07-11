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
        Schema::create('equipo_trabajo_servicios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('equipo_trabajo_id')->nullable();
            $table->unsignedBigInteger('servicio_id')->nullable();
            $table->string('estado')->nullable();
            $table->timestamps();
            $table->foreign('equipo_trabajo_id')->references('id')->on('equipo_trabajos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('servicio_id')->references('id')->on('servicios')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipo_trabajo_servicios');
    }
};
