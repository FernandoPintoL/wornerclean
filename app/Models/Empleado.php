<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

/**
 *
 *
 * @property int $id
 * @property string|null $ci
 * @property string|null $nombre
 * @property string|null $telefono
 * @property string|null $puesto
 * @property string|null $estado
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleado newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleado newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleado query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleado whereCi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleado whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleado whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleado whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleado whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleado wherePuesto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleado whereTelefono($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleado whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleado whereUserId($value)
 * @mixin \Eloquent
 */
class Empleado extends Model
{
    use HasRoles;

    protected $table = 'empleados';
    protected $primaryKey = 'id';
    protected $fillable = [
        'ci',
        'nombre',
        'email',
        'telefono',
        'puesto',
        'estado',
        'user_id'
    ];

    /**
     * Get the work teams associated with the employee.
     */
    public function equiposTrabajo()
    {
        return $this->belongsToMany(EquipoTrabajo::class, 'empleado_equipo_trabajos', 'empleado_id', 'equipo_trabajo_id')
                    ->withPivot('estado', 'ocupacion')
                    ->withTimestamps();
    }
}
