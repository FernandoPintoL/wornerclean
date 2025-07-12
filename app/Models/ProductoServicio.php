<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int|null $producto_id
 * @property int|null $servicio_id
 * @property float|null $cantidad
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductoServicio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductoServicio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductoServicio query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductoServicio whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductoServicio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductoServicio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductoServicio whereProductoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductoServicio whereServicioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductoServicio whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductoServicio extends Model
{
    protected $table = 'producto_servicios';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cantidad' // Solo para productos
    ];
}
