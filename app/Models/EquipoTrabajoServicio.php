<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property int|null $equipo_trabajo_id
 * @property int|null $servicio_id
 * @property string|null $estado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipoTrabajoServicio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipoTrabajoServicio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipoTrabajoServicio query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipoTrabajoServicio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipoTrabajoServicio whereEquipoTrabajoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipoTrabajoServicio whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipoTrabajoServicio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipoTrabajoServicio whereServicioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EquipoTrabajoServicio whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EquipoTrabajoServicio extends Model
{
    protected $table = 'equipo_trabajo_servicios';
    protected $primaryKey = 'id';
    protected $fillable = [
        'equipo_trabajo_id',
        'servicio_id',
        'estado'
    ];

    /**
     * Get the work team that owns the relationship.
     */
    public function equipoTrabajo()
    {
        return $this->belongsTo(EquipoTrabajo::class, 'equipo_trabajo_id');
    }

    /**
     * Get the service that owns the relationship.
     */
    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }
}
