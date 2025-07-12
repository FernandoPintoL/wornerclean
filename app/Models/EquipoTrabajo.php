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
 * @property string|null $estado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipoTrabajo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipoTrabajo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipoTrabajo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipoTrabajo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipoTrabajo whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipoTrabajo whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipoTrabajo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipoTrabajo whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipoTrabajo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EquipoTrabajo extends Model
{
    protected $table = 'equipo_trabajos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
        'user_id'
    ];
}
