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
 * @property float|null $stock
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Producto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Producto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Producto query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Producto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Producto whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Producto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Producto whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Producto wherePrecio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Producto whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Producto whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Producto extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock'
    ];
}
