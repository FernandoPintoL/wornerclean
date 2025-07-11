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
        Schema::create('contrato_incidencias', function (Blueprint $table) {
            $table->id();
            $table->string('estado')->nullable();
            $table->string('descripcion')->nullable();
            $table->timestamp('fecha_solucion')->nullable()->default(null);
            $table->foreignId('contrato_id')->constrained('contratos')->onDelete('cascade');
            $table->foreignId('incidencia_id')->constrained('incidencias')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrato_incidencias');
    }
};
