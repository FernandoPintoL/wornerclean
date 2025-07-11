<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    /** @use HasFactory<\Database\Factories\ContratoFactory> */
    use HasFactory;
    protected $table = 'contratos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'descripcion',
        'costo_total',
        'estado',
        'fecha_inicio',
        'fecha_fin',
        'estado_pago',
        'monto_pagado',
        'monto_saldo',
        'servicio_id',
        'cliente_id'
    ];
}
