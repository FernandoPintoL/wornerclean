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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Incidencias newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Incidencias newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Incidencias query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Incidencias whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Incidencias whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Incidencias whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Incidencias whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Incidencias whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Incidencias extends Model
{
    protected $table = 'incidencias';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre',
        'descripcion'
    ];
}
