<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string|null $descripcion
 * @property float|null $costo_total
 * @property string|null $estado
 * @property string|null $fecha_inicio
 * @property string|null $fecha_fin
 * @property string|null $estado_pago
 * @property float|null $monto_pagado
 * @property float|null $monto_saldo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $cliente_id
 * @property int|null $servicio_id
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contrato newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contrato newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contrato query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contrato whereClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contrato whereCostoTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contrato whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contrato whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contrato whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contrato whereEstadoPago($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contrato whereFechaFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contrato whereFechaInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contrato whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contrato whereMontoPagado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contrato whereMontoSaldo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contrato whereServicioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contrato whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Contrato extends Model
{
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
