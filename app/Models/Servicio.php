<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string|null $nombre
 * @property string|null $descripcion
 * @property float|null $precio
 * @property string|null $frecuencia
 * @property string|null $estado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Servicio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Servicio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Servicio query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Servicio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Servicio whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Servicio whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Servicio whereFrecuencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Servicio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Servicio whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Servicio wherePrecio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Servicio whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Servicio extends Model
{
    protected $table = 'servicios';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'frecuencia',
        'estado'
    ];
}
