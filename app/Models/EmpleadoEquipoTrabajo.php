<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int|null $empleado_id
 * @property int|null $equipo_trabajo_id
 * @property string|null $estado
 * @property string|null $ocupacion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmpleadoEquipoTrabajo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmpleadoEquipoTrabajo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmpleadoEquipoTrabajo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmpleadoEquipoTrabajo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmpleadoEquipoTrabajo whereEmpleadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmpleadoEquipoTrabajo whereEquipoTrabajoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmpleadoEquipoTrabajo whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmpleadoEquipoTrabajo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmpleadoEquipoTrabajo whereOcupacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmpleadoEquipoTrabajo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EmpleadoEquipoTrabajo extends Model
{
    protected $table = 'empleado_equipo_trabajos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'empleado_id',
        'equipo_trabajo_id',
        'estado',
        'ocupacion'
    ];
}
